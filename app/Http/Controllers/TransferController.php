<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionItem;
use App\Models\InventoryTransactionItemProperty;
use App\Models\InventoryTransactionItemPropertyCurrentKeeper;
use App\Models\PropertyTransfer;
use App\Models\PropertyTransferIssuer;
use App\Models\PropertyTransferProperty;
use App\Models\PropertyTransferReceiver;
use App\Models\SupplyEndUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public $notApplicableVariants = [
        'N/A',
        'Not Applicable',
        'n/a',
        'NA',
        'na',
        'N.A.',
        'n.a.',
        'N/Appl.',
        'n/appl.',
        'not applicable',
        'not available',
        'not apply',
        'Not Apply',
        'no data',
        'No Data',
        'Nill',
        'None or Not Applicable',
        'nill',
        '--',
        'TBD',
        'To Be Determined',
        'tbd',
        'TBA',
        'To Be Announced',
        'tba',
        'TBC',
        'To Be Confirmed',
        'tbc',
        'N/S',
        'Not Specified',
        'n/s',
        'N/R',
        'Not Required',
        'n/r',
        'N/D',
        'n/d',
        'N/V',
        'Not Valid',
        'n/v',
    ];

    public function all_transfers()
    {
        $allTransfers = PropertyTransfer::all();

        return view('so-dashboard.all-transfers')
            ->with('allTransfers', $allTransfers);
    }

    public function all_ics()
    {
        // return InventoryTransaction::with(['purchase_order', 'items.bac_reso_item', 'issuers', 'receivers', 'items.properties.transfers'])
        // ->where('type', '<>', 'PAR')
        // ->get();
        try {
            $ics = InventoryTransaction::with(['purchase_order', 'items.bac_reso_item', 'issuers', 'receivers', 'items.properties.transfers'])
                ->where('type', '<>', 'PAR')
                ->get();

            return view('so-dashboard.all-ics')
                ->with('ics', $ics);
        } catch (\Exception $e) {
            // return $e;
            return redirect()->route('dashboard.show');
        }
    }

    public function all_par()
    {
        try {
            $par = InventoryTransaction::with(['purchase_order', 'items.bac_reso_item', 'issuers', 'receivers', 'items.properties.transfers'])
                ->where('type', 'PAR')
                ->get();

            return view('so-dashboard.all-par')
                ->with('par', $par);
        } catch (\Exception $e) {
            // return $e;
            return redirect()->to('/')->withErrors(['Cannot get records. Please try again later or contact web developer.']);
        }
    }

    public function transfer_item($id)
    {
        try {
            $tranItem = InventoryTransactionItem::whereHas('properties', function ($builder) {
                $builder->doesntHave('transfers');
            })
                ->where('id', $id)
                ->with(['properties' => function ($builder) {
                    $builder->doesntHave('transfers');
                }, 'bac_reso_item.quotation.pr_item.ppmp.item_detail.unit'])
                ->first();

            $endUsers = SupplyEndUser::all();

            return view('so-dashboard.transfer-item')
                ->with('tranItem', $tranItem)
                ->with('endUsers', $endUsers);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.show');
        }
    }

    public function post_transfer(Request $request, $id)
    {
        //Validate the inputs of the users.
        // TODO: do the other inputs and other references to other tables if valid for the user.
        $request->validate([
            'selected_serial_numbers.*' => 'array|exists:inventory_transaction_item_properties,id',
            'quantity' => 'integer|numeric',
            'reason' => 'required',
            'transfer_type' => 'required|in:OTHERS,DONATION,RELOCATE,REASSIGNMENT',
            'receiver' => 'required|exists:supply_end_users,id',
            'issuer' => 'exists:supply_end_users,id'
        ]);

        try {
            DB::beginTransaction();

            //PTR number builder
            $latestPt = PropertyTransfer::latest()
                ->first();

            $ptNumCtr = $latestPt === null ? 1 : intval(substr($latestPt->number, 9, 4)) + 1;

            $ptNumber = sprintf(
                '%s-%s-%s',
                date('Y'),
                date('m'),
                str_pad($ptNumCtr, 4, '0', STR_PAD_LEFT),
            );

            $newProperty = new PropertyTransfer([
                'inventory_transaction_items_id' => $id,
                'number' => $ptNumber,
                'quantity' => $request->quantity ?? count(json_decode($request->selected_serial_numbers)),
                'reason' => $request->reason,
                'type' => $request->transfer_type,
                'other_type' => $request->other_type,
                'added_by' => Auth::user()->id,
            ]);
            $newProperty->save();

            //Fetching the itemss + properties so we can get the available properties and also to check if the property have a valid serial number. In case it's n/a, we will just get random properties that are available.
            $itemWithProperties = InventoryTransactionItem::with(['properties' => function ($builder) {
                $builder
                    ->doesntHave('transfers')
                    ->whereIn('serial_number', $this->notApplicableVariants);
            }, 'transaction.receivers'])
                ->find($id);

            $newPropertyTransferReceiver = new PropertyTransferReceiver([
                'property_transfers_id' => $newProperty->id,
                'supply_end_users_id' => $request->receiver,
            ]);
            $newPropertyTransferReceiver->save();

            $transferIssuer = $request->issuer;
            if ($transferIssuer === null) {
                $transferIssuer = $itemWithProperties->transaction->receivers[0]->supply_end_users_id;
            }

            $newPropertyTransferIssuer = new PropertyTransferIssuer([
                'property_transfers_id' => $newProperty->id,
                'supply_end_users_id' => $transferIssuer,
            ]);

            $newPropertyTransferIssuer->save();

            if ($itemWithProperties->properties->isEmpty()) {
                //save the properties with serial numbers here
                foreach (json_decode($request->selected_serial_numbers) as $serialNumber) {
                    $newPropertyTransferProperty = new PropertyTransferProperty([
                        'property_transfers_id' => $newProperty->id,
                        'inventory_transaction_item_properties_id' => $serialNumber,
                    ]);
                    $newPropertyTransferProperty->save();

                    InventoryTransactionItemPropertyCurrentKeeper::where('inventory_transaction_item_properties_id', $serialNumber)->delete();

                    $newKeeper = new InventoryTransactionItemPropertyCurrentKeeper([
                        'inventory_transaction_item_properties_id' => $serialNumber,
                        'supply_end_users_id' => $request->receiver,
                    ]);
                    $newKeeper->save();
                }
            } else {
                //save the properties without serial numbers
                //we will be looping through the available properties instead of specific properties
                // $newPropertyTransferIssuer = new PropertyTransferIssuer([
                //     'property_transfers_id' => $newProperty->id,
                //     'supply_end_users_id' => $itemWithProperties->transaction->receivers[0]->supply_end_users_id,
                // ]);
                // $newPropertyTransferIssuer->save();
                for ($i = 0; $i < $request->quantity; $i++) {
                    $newPropertyTransferProperty = new PropertyTransferProperty([
                        'property_transfers_id' => $newProperty->id,
                        'inventory_transaction_item_properties_id' => $itemWithProperties->properties[$i]->id,
                    ]);
                    $newPropertyTransferProperty->save();

                    InventoryTransactionItemPropertyCurrentKeeper::where('inventory_transaction_item_properties_id', $itemWithProperties->properties[$i]->id)->delete();

                    $newKeeper = new InventoryTransactionItemPropertyCurrentKeeper([
                        'inventory_transaction_item_properties_id' => $itemWithProperties->properties[$i]->id,
                        'supply_end_users_id' => $request->receiver,
                    ]);
                    $newKeeper->save();
                }
            }
            DB::commit();
            return redirect()->route('transfers.all')->with('success', 'Property transfer has been successfully processed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Please check your inputs if correct. If the problem persists please contact the administrator.']);
        }
    }

    public function user_to_user_transfer($propertyId)
    {
        $item = InventoryTransactionItemProperty::where('id', $propertyId)
            ->where('is_available', true)
            ->has('transfers')
            ->first();

        if ($item === null) {
            return redirect()->back()->withErrors(['Item not found.']);
        }

        $endUsers = SupplyEndUser::whereNotIn('id', $item->current_owners->pluck('supply_end_users_id')->toArray())->get();

        return view('so-dashboard.transfer-user-to-user')
            ->with('item', $item)
            ->with('endUsers', $endUsers);
    }

    public function transfer_user_to_user(Request $request, $propertyId)
    {
        $request->validate([
            'reason' => 'required',
            'issuer' => 'exists:supply_end_users,id'
        ]);

        try {
            DB::beginTransaction();

            $itemProperty = InventoryTransactionItemProperty::with(['item', 'current_owners'])
                ->has('transfers')
                ->find($propertyId);

            //PTR number builder
            $latestPt = PropertyTransfer::latest()
                ->first();
            $ptNumCtr = $latestPt === null ? 1 : intval(substr($latestPt->number, 9, 4)) + 1;
            $ptNumber = sprintf(
                '%s-%s-%s',
                date('Y'),
                date('m'),
                str_pad($ptNumCtr, 4, '0', STR_PAD_LEFT),
            );

            $newProperty = new PropertyTransfer([
                'inventory_transaction_items_id' => $itemProperty->item->id,
                'number' => $ptNumber,
                'quantity' => 1,
                'reason' => $request->reason,
                'added_by' => Auth::user()->id,
            ]);
            $newProperty->save();

            $newPropertyTransferProperty = new PropertyTransferProperty([
                'property_transfers_id' => $newProperty->id,
                'inventory_transaction_item_properties_id' => $propertyId,
            ]);
            $newPropertyTransferProperty->save();

            $newPropertyTransferIssuer = new PropertyTransferIssuer([
                'property_transfers_id' => $newProperty->id,
                'supply_end_users_id' => $itemProperty->current_owners[0]->end_user->id,
            ]);
            $newPropertyTransferIssuer->save();

            $newPropertyTransferReceiver = new PropertyTransferReceiver([
                'property_transfers_id' => $newProperty->id,
                'supply_end_users_id' => $request->receiver,
            ]);
            $newPropertyTransferReceiver->save();

            //replace the property's keeper
            InventoryTransactionItemPropertyCurrentKeeper::where('inventory_transaction_item_properties_id', $propertyId)->delete();
            $newPropertyKeeper = new InventoryTransactionItemPropertyCurrentKeeper([
                'inventory_transaction_item_properties_id' => $propertyId,
                'supply_end_users_id' => $request->receiver,
            ]);
            $newPropertyKeeper->save();

            DB::commit();
            return redirect()->route('transfers.all')->with('success', 'Property transfer has been successfully processed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Please check your inputs if correct. If the problem persists please contact the administrator.']);
        }
    }

    public function print_transfer($transferId)
    {
        try {
            $transfer = PropertyTransfer::find($transferId);
            return view('so-dashboard.print-transfer')
                ->with('transfer', $transfer);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Invalid transfer ID.']);
        }
    }
}
