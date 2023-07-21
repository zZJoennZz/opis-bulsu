<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\PropertyTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function all_transfers() {
        $allTransfers = PropertyTransfer::all();

        return view('so-dashboard.all-transfers')
            ->with('allTransfers', $allTransfers);
    }

    public function all_ics() {
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
            return redirect()->route('dashboard.show')->withErrors(['Cannot get records. Please try again later or contact web developer.']);
        }
    }

    public function all_par() {
        try {
            $par = InventoryTransaction::with(['purchase_order', 'items.bac_reso_item', 'items.serial_numbers', 'issuers', 'receivers', 'items.transfers'])
                ->where('type', 'PAR')    
                ->get();

            return view('so-dashboard.all-par')
                ->with('par', $par);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.show');
        }
    }
    public function prepare($itemId) {
        $inventoryItem = InventoryTransactionItem::find($itemId);

        $getAllTransfers = $inventoryItem->transfers;

        if ($inventoryItem->quantity === count($getAllTransfers)) {
            return redirect()
                ->back()
                ->withErrors(['Items are already transferred.']);
        }

        $supplyEndUsers = SupplyEndUser::all();
        return view('so-dashboard.prepare-item-transfer')
            ->with('inventoryItem', $inventoryItem)
            ->with('supplyEndUsers', $supplyEndUsers);
    }

    public function submit_transfer(Request $request, $itemId)
    {
        $getItem = InventoryTransactionItem::find($itemId);
        
        $request->validate([
            'issuer' => [
                'required',
                'exists:supply_end_users,id',
                'not_in:' . $request->receiver,
            ],
            'receiver' => [
                'required',
                'exists:supply_end_users,id',
                'not_in:' . $request->issuer,
            ],
            'serialNumbers' => 'array',
            'serialNumbers.*' => 'exists:inventory_transaction_item_serial_numbers,id',
            'reason' => 'required|max:255',
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:'.$getItem->quantity,
            ],
        ]);
        
        $serialNumbers = $request->input('serialNumbers', []);
        
        $isSerialAlreadyTransferred = InventoryTransactionItemSerialNumber::whereIn('id', $serialNumbers)
            ->has('current_end_user')
            ->count();
        
        if ($isSerialAlreadyTransferred > 0) {
            return redirect()
                ->back()
                ->withErrors(['Selected item/s under selected serial number/s is/are already transferred.']);
        }
        
        $checkIfItemHasSerialNumbers = InventoryTransactionItem::where('id', $itemId)
            ->has('serial_numbers')
            ->count();
            
        $checkIfSerialNumbersAreValid = InventoryTransactionItemSerialNumber::where('inventory_transaction_items_id', $itemId)
            ->whereIn('id', $serialNumbers)
            ->count();
        
        $isSerialRequired = $checkIfItemHasSerialNumbers > 0 ? true : false;
        $isSelectedSerialNumbersValid = $checkIfSerialNumbersAreValid === count($serialNumbers) ? true : false;
        
        if ($isSerialRequired && !$isSelectedSerialNumbersValid) {
            return redirect()
                ->back()
                ->withErrors(['Invalid serial numbers.']);
        }
        
        if ($isSerialRequired && count($serialNumbers) !== intval($request->quantity)) {
            return redirect()
                ->back()
                ->withErrors(['Please select the same amount of serial numbers relative to quantity.']);
        }
        
        try {
            DB::beginTransaction();
            
            $latest_transfer = InventoryTransfer::whereYear('created_at', date('Y'))
                ->latest()
                ->first();
                
            $latest_transfer_ctr = $latest_transfer === null ? 1 : intval(substr($latest_transfer->number, 9, 3)) + 1;
            
            $ptr_number = date('Y') . "-" . date('m') . "-" . str_pad($latest_transfer_ctr, 3, '0', STR_PAD_LEFT);
            
            $newTransfer = InventoryTransfer::create([
                'number' => $ptr_number,
                'reason' => $request->reason,
                'quantity' => $request->quantity,
                'inventory_transaction_items_id' => $itemId,
                'added_by' => Auth::user()->id,
            ]);
            
            InventoryTransferIssuer::create([
                'inventory_transfers_id' => $newTransfer->id,
                'supply_end_users_id' => $request->issuer,
            ]);
            
            InventoryTransferReceiver::create([
                'inventory_transfers_id' => $newTransfer->id,
                'supply_end_users_id' => $request->receiver,
            ]);
            
            if ($isSerialRequired) {
                foreach ($serialNumbers as $serialNumber) {
                    InventoryTransferItem::create([
                        'inventory_transfers_id' => $newTransfer->id,
                        'inventory_transaction_item_serial_numbers_id' => $serialNumber,
                    ]);
                }
            }
            
            DB::commit();

            $routeAfterSubmission = "";
            if ($getItem->transaction->type === "PAR") {
                $routeAfterSubmission = "par.all";
            } else {
                $routeAfterSubmission = "ics.all";
            }
            
            return redirect()
                ->route($routeAfterSubmission)
                ->with('success', 'Property successfully transferred.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withErrors(['Something went wrong. Please try again or contact the web developer.']);
        }
    }


    // public function submit_transfer(Request $request, $itemId) {
    //     //validating the transfer request inputs
    //     $getItem = InventoryTransactionItem::find($itemId);
    //     $request->validate([
    //         'issuer' => 'required|exists:supply_end_users,id|not_in:' . $request->receiver,
    //         'receiver' => 'required|exists:supply_end_users,id|not_in:' . $request->issuer,
    //         'serialNumbers' => 'array',
    //         'serialNumbers.*' => 'exists:inventory_transaction_item_serial_numbers,id',
    //         'reason' => 'required|max:255',
    //         'quantity' => 'required|integer|min:1|max:'.$getItem->quantity,
    //     ]);
    //     $serialNumbers = $request->serialNumbers ?? [];
    //     $isSerialAlreadyTransferred = InventoryTransactionItemSerialNumber::whereIn('id', $serialNumbers)->has('current_end_user')->get();
    //     if (count($isSerialAlreadyTransferred) > 0) {
    //         return redirect()->back()->withErrors(['Selected item/s under selected serial number/s is/are already transferred.']);
    //     }
        
    //     $checkIfItemHasSerialNumbers = InventoryTransactionItem::where('id', $itemId)->has('serial_numbers')->get();
    //     $checkIfSerialNumbersAreValid = InventoryTransactionItemSerialNumber::where('inventory_transaction_items_id', $itemId)->whereIn('id', $serialNumbers)->get();
    //     $isSerialRequired = count($checkIfItemHasSerialNumbers) > 0 ? true : false;
    //     $isSelectedSerialNumbersValid = count($checkIfSerialNumbersAreValid) === count($serialNumbers) ? true : false;
    //     if ($isSerialRequired && !$isSelectedSerialNumbersValid) {
    //         return redirect()->back()->withErrors(['Invalid serial numbers.']);
    //     }
        
    //     if ($isSerialRequired && (count($serialNumbers) !== intval($request->quantity))) {
    //         return redirect()->back()->withErrors(['Please select same amount of serial numbers relative to quantity.']);
    //     }

    //     //begin transfer
    //     try {
    //         DB::beginTransaction();
    //         //ptr number builder
    //         $latest_transfer = InventoryTransfer::where((DB::raw('YEAR(created_at)')), date('Y'))
    //             ->latest()
    //             ->first();

    //         $latest_transfer_ctr = $latest_transfer === null ? 1 : intval(substr($latest_transfer->number, 9, 3)) + 1;

    //         $ptr_number = date('Y') . "-" . date('m') . "-" . str_pad($latest_transfer_ctr, 3, '0', STR_PAD_LEFT);

    //         $newTransfer = new InventoryTransfer([
    //             'number' => $ptr_number,
    //             'reason' => $request->reason,
    //             'quantity' => $request->quantity,
    //             'inventory_transaction_items_id' => $itemId,
    //             'added_by' => Auth::user()->id,
    //         ]);
    //         $newTransfer->save();

    //         $newIssuer = new InventoryTransferIssuer([
    //             'inventory_transfers_id' => $newTransfer->id,
    //             'supply_end_users_id' => $request->issuer,
    //         ]);
    //         $newIssuer->save();

    //         $newReceiver = new InventoryTransferReceiver([
    //             'inventory_transfers_id' => $newTransfer->id,
    //             'supply_end_users_id' => $request->receiver
    //         ]);
    //         $newReceiver->save();

    //         if ($isSerialRequired) {
    //             for ($i = 0; $i < $request->quantity; $i++) {
    //                 $newItem = new InventoryTransferItem([
    //                     'inventory_transfers_id' => $newTransfer->id,
    //                     'inventory_transaction_item_serial_numbers_id' => $request->serialNumbers[$i],
    //                 ]);
    //                 $newItem->save();
    //             }
    //         }
            
    //         DB::commit();
    //         return redirect()
    //             ->route('ics.all')
    //             ->with('success', 'Property successfully transferred.');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return redirect()
    //             ->back()
    //             ->withErrors(['Something went wrong. Please try again or contact web developer.']);
    //     }
    // }
}
