<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransactionItem;
use App\Models\InventoryTransactionItemSerialNumber;
use App\Models\InventoryTransfer;
use App\Models\InventoryTransferIssuer;
use App\Models\InventoryTransferItem;
use App\Models\InventoryTransferReceiver;
use App\Models\SupplyEndUser;
use Illuminate\Http\Request;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function all_ics() {
        try {
            $ics = InventoryTransaction::with(['purchase_order', 'items.bac_reso_item', 'items.serial_numbers', 'issuers', 'receivers', 'items.transfers'])
                ->where('type', '<>', 'PAR')    
                ->get();
            return view('so-dashboard.all-ics')
                ->with('ics', $ics);
        } catch (\Exception $e) {
            // return $e;
            return redirect()->route('dashboard.show')->withErrors(['Cannot get records. Please try again later or contact web developer.']);
        }
    }

    public function prepare($itemId) {
        $inventoryItem = InventoryTransactionItem::find($itemId);
        $supplyEndUsers = SupplyEndUser::all();
        return view('so-dashboard.prepare-item-transfer')
            ->with('inventoryItem', $inventoryItem)
            ->with('supplyEndUsers', $supplyEndUsers);
    }

    public function submit_transfer(Request $request, $itemId) {

        //validating the request fields
        $getItem = InventoryTransactionItem::find($itemId);
        $request->validate([
            'issuer' => 'required|exists:supply_end_users,id|not_in:' . $request->receiver,
            'receiver' => 'required|exists:supply_end_users,id|not_in:' . $request->issuer,
            'serialNumbers' => 'array',
            'serialNumbers.*' => 'exists:inventory_transaction_item_serial_numbers,id',
            'reason' => 'required|max:255',
            'quantity' => 'required|integer|min:1|max:'.$getItem->quantity,
        ]);
        $serialNumbers = $request->serialNumbers ?? [];
        $isSerialAlreadyTransferred = InventoryTransactionItemSerialNumber::whereIn('id', $serialNumbers)->has('current_end_user')->get();
        if (count($isSerialAlreadyTransferred) > 0) {
            return redirect()->back()->withErrors(['Selected item/s under selected serial number/s is/are already transferred.']);
        }
        $isSerialRequired = InventoryTransactionItem::has('serial_numbers')->get();
        if (count($isSerialRequired) > 0 && count($serialNumbers) === 0) {
            return redirect()->back()->withErrors(['Please select serial number to transfer.']);
        }
        
        try {
            DB::beginTransaction();
            //ptr number builder
            $latest_transfer = InventoryTransfer::where((DB::raw('YEAR(created_at)')), date('Y'))
                ->latest()
                ->first();

            $latest_transfer_ctr = $latest_transfer === null ? 1 : intval(substr($latest_transfer->number, 9, 3)) + 1;

            $ptr_number = date('Y') . "-" . date('m') . "-" . str_pad($latest_transfer_ctr, 3, '0', STR_PAD_LEFT);

            $newTransfer = new InventoryTransfer([
                'number' => $ptr_number,
                'reason' => $request->reason,
                'quantity' => $request->quantity,
                'inventory_transaction_items_id' => $itemId,
                'added_by' => Auth::user()->id,
            ]);
            $newTransfer->save();

            $newIssuer = new InventoryTransferIssuer([
                'inventory_transfers_id' => $newTransfer->id,
                'supply_end_users_id' => $request->issuer,
            ]);
            $newIssuer->save();

            $newReceiver = new InventoryTransferReceiver([
                'inventory_transfers_id' => $newTransfer->id,
                'supply_end_users_id' => $request->receiver
            ]);
            $newReceiver->save();

            for ($i = 0; $i < $request->quantity; $i++) {
                $newItem = new InventoryTransferItem([
                    'inventory_transfers_id' => $newTransfer->id,
                    'inventory_transaction_item_serial_numbers_id' => $request->serialNumbers[$i],
                ]);
                $newItem->save();
            }
            DB::rollBack();
            return redirect()
                ->route('prepare-transfer.show')
                ->with('success', 'Property successfully transferred.');
        } catch (\Exception $e) {
            DB::rollBack();
            return "aw.";
        }
    }
}
