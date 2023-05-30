<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCode;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionIssuer;
use App\Models\InventoryTransactionItem;
use App\Models\InventoryTransactionItemSerialNumber;
use App\Models\InventoryTransactionReceiver;
use App\Models\PurchaseOrder;
use App\Models\SupplyEndUser;
use App\Models\SupplyOfficeEmployee;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function add_ics_l()
    {
        try {
            $pos = PurchaseOrder::where('year', getPpmpYear())
                ->with(['bac_reso.bac_reso_items', 'bac_reso.bac_reso_items.supply_inventory_item', 'transactions.items', 'company' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->whereHas('bac_reso.bac_reso_items.quotation', function ($builder) {
                    $builder->whereBetween('offered_unit_price', [0, 4999.99]);
                })
                ->get();

            $ics_po = [];

            foreach ($pos as $po) {
                $itemCtr = 0;
                foreach ($po->transactions as $tran) {
                    // return $tran;
                    $itemCtr += count($tran->items);
                }
                if (count($po->bac_reso->bac_reso_items) !== $itemCtr) {
                    array_push($ics_po, $po);
                }
            }

            $eq_code = EquipmentCode::all();

            $end_users = SupplyEndUser::with(['position', 'branch'])->get();
            $supply_emp = SupplyOfficeEmployee::with(['position'])->get();

            return view('so-dashboard.inventory-custodian-slip')
                ->with('type', "ICSL")
                ->with('ics_po', $ics_po)
                ->with('eq_code', $eq_code)
                ->with('end_users', $end_users)
                ->with('supply_emp', $supply_emp);
        } catch (\Exception $e) {
            return redirect()->route('so-dashboard.show')->withErrors(['Something went wrong. Cannot load ICS - LOW VALUE.']);
        }
    }

    public function add_ics_h()
    {
        try {
            // $ics_po = PurchaseOrder::where('year', getPpmpYear())
            //     ->where('is_delete', 0)
            //     ->with(['company'])
            //     ->whereHas('bac_reso.bac_reso_items.quotation', function ($builder) {
            //         $builder->where('offered_unit_price', '<', '50000')->where('offered_unit_price', '>=', '5000');
            //     })
            //     ->get();

            $pos = PurchaseOrder::where('year', getPpmpYear())
                ->with(['bac_reso.bac_reso_items.supply_inventory_item', 'transactions.items', 'company' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->whereHas('bac_reso.bac_reso_items.quotation', function ($builder) {
                    $builder->whereBetween('offered_unit_price', [5000, 49999.99]);
                })
                ->get();

            $ics_po = [];

            foreach ($pos as $po) {
                $itemCtr = 0;
                foreach ($po->transactions as $tran) {
                    $itemCtr += count($tran->items);
                }
                if (count($po->bac_reso->bac_reso_items) !== $itemCtr) {
                    array_push($ics_po, $po);
                }
            }


            $eq_code = EquipmentCode::all();
            $end_users = SupplyEndUser::with(['position', 'branch'])->get();
            $supply_emp = SupplyOfficeEmployee::with(['position'])->get();

            return view('so-dashboard.inventory-custodian-slip')
                ->with('type', "ICSH")
                ->with('ics_po', $ics_po)
                ->with('eq_code', $eq_code)
                ->with('end_users', $end_users)
                ->with('supply_emp', $supply_emp);
        } catch (\Exception $e) {
            return redirect()->route('so-dashboard.show')->withErrors(['Something went wrong. Cannot load ICS - HIGH VALUE.']);
        }
    }

    public function save_ics(Request $request, $type)
    {
        if ($type === "" || $type === null || ($type !== "ICSL" && $type !== "ICSH")) {
            return response()->json([
                'success' => false,
                'message' => "Invalid action.",
            ], 400);
        }
        $customMessages = [
            'purchaseOrderId.required' => 'Please select a purchase order.',
            'dateAcquired.required' => 'Please enter the date acquired.',
            'dateIssued.required' => 'Please enter the date issued.',
            'issuedBy.required' => 'Please select an issuer.',
            'issuedBy.exists' => 'Please select valid issuer.',
            'receivedBy.required' => 'Please select a receiver.',
            'receivedBy.exists' => 'Please select valid receiver.',
            'purchaseOrderItems.required' => 'Please provide purchase order items.',
            'purchaseOrderItems.*.equipmentCode.required' => 'Please select an equipment code for all purchase order items.',
            'purchaseOrderItems.*.serialNumbers.array' => 'Invalid value for serial numbers.',
        ];
        $request->validate([
            'purchaseOrderId' => 'required|exists:purchase_orders,id',
            'dateAcquired' => 'required',
            'dateIssued' => 'required',
            'issuedBy' => 'required|exists:supply_office_employees,id',
            'receivedBy' => 'required|exists:supply_end_users,id',
            'purchaseOrderItems' => 'required|array',
            'purchaseOrderItems.*.equipmentCode' => 'required',
            'purchaseOrderItems.*.serialNumbers' => 'array',
        ], $customMessages);

        DB::beginTransaction();
        $branch = SupplyEndUser::with(['branch'])->where('id', $request->receivedBy)->first();

        //ics number builder
        $latest_ics = InventoryTransaction::where((DB::raw('YEAR(date_issued)')), date('Y', strtotime($request->dateIssued)))
            ->where('type', $type)
            ->latest()
            ->first();

        $ics_num_ctr = $latest_ics === null ? 1 : intval(substr($latest_ics->number, 9, 4)) + 1;

        $type_prefix = $type === "ICSL" ? "L" : "H";
        $ics_number = date('Y', strtotime($request->dateIssued)) . "-" . date('m') . "-" . $type_prefix . str_pad($ics_num_ctr, 3, '0', STR_PAD_LEFT);

        $new_ics = new InventoryTransaction([
            "type" => $type,
            "number" => $ics_number,
            "date_acquired" => $request->dateAcquired,
            "purchase_orders_id" => $request->purchaseOrderId,
            "date_issued" => $request->dateIssued,
            "added_by" => Auth::user()->id,
        ]);

        $new_ics->save();

        $po_id = PurchaseOrder::find($request->purchaseOrderId);
        $withCond = "";
        if ($type === "ICSL") {
            $withCond = [
                'bac_reso.bac_reso_items.quotation' => function ($builder) use ($po_id) {
                    $builder->where('offered_unit_price', '<', '5000')
                        ->whereHas('quotation', function ($builder1) use ($po_id) {
                            $builder1->where('companies_id', $po_id->companies_id);
                        });
                },
                'bac_reso.bac_reso_items.quotation.quotation' => function ($builder) use ($po_id) {
                    $builder->where('companies_id', $po_id->companies_id);
                },
                'bac_reso.bac_reso_items.quotation.pr_item.ppmp.item_detail.unit',
                'bac_reso.bac_reso_items.quotation.pr_item.ppmp.milestones'
            ];
        } else {
            $withCond = [
                'bac_reso.bac_reso_items.quotation' => function ($builder) use ($po_id) {
                    $builder->where('offered_unit_price', '<', '50000')
                        ->where('offered_unit_price', '>=', '5000')
                        ->whereHas('quotation', function ($builder1) use ($po_id) {
                            $builder1->where('companies_id', $po_id->companies_id);
                        });
                },
                'bac_reso.bac_reso_items.quotation.quotation' => function ($builder) use ($po_id) {
                    $builder->where('companies_id', $po_id->companies_id);
                },
                'bac_reso.bac_reso_items.quotation.pr_item.ppmp.item_detail.unit',
                'bac_reso.bac_reso_items.quotation.pr_item.ppmp.milestones'
            ];
        }
        $po = PurchaseOrder::with($withCond)
            ->where('id', $request->purchaseOrderId)
            ->first();
        $ctr = 0;
        foreach ($po->bac_reso->bac_reso_items as $item) {
            if ($item->quotation !== null) {
                // return $item->quotation->quotation;
                $eqCode = EquipmentCode::find($request->purchaseOrderItems[$ctr]['equipmentCode']);

                $totalQty = 0;
                foreach ($item->quotation->pr_item->ppmp->milestones as $m) {
                    $totalQty += $m->milestone_value;
                }

                $property_no = sprintf(
                    '%s-%s-%s-%s',
                    date('Y', strtotime($request->date_issued)),
                    "SE",
                    $eqCode->unique_code,
                    $branch->branch->office_code,
                );


                $new_ics_item = new InventoryTransactionItem([
                    "inventory_transactions_id" => $new_ics->id,
                    "b_a_c_reso_items_id" => $item->id,
                    "quantity" => $totalQty,
                    "equipment_codes_id" => $request->purchaseOrderItems[$ctr]['equipmentCode'],
                    "property_number" => $property_no,
                ]);

                $new_ics_item->save();

                if ($request->purchaseOrderItems[$ctr]['serialNumbers'] !== "" && $request->purchaseOrderItems[$ctr]['serialNumbers'] !== null) {
                    foreach ($request->purchaseOrderItems[$ctr]['serialNumbers'] as $sn) {
                        $new_ics_item_sn = new InventoryTransactionItemSerialNumber([
                            "inventory_transaction_items_id" => $new_ics_item->id,
                            "serial_number" => $sn,
                        ]);

                        $new_ics_item_sn->save();
                    }
                }

                $ctr += 1;
            }
        }

        $new_issuer = new InventoryTransactionIssuer([
            "inventory_transactions_id" => $new_ics->id,
            "supply_office_employees_id" => $request->issuedBy,
        ]);
        $new_issuer->save();

        $new_receiver = new InventoryTransactionReceiver([
            "inventory_transactions_id" => $new_ics->id,
            "supply_end_users_id" => $request->receivedBy,
        ]);
        $new_receiver->save();

        DB::commit();
        back()->with('success', 'Inventory custodian slip created.');

        return response()->json([
            'success' => true,
            'redirect' => route('so-dashboard.show'),
        ], 200);

        try {
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ], 400);
        }
    }

    //to be removed
    public function add()
    {
        try {
            $ics_po = PurchaseOrder::where('year', getPpmpYear())
                ->where('is_delete', 0)
                ->with(['company'])
                ->whereDoesntHave('transaction', function ($builder) {
                    $builder->where('type', 'ICS');
                })
                ->get();

            $eq_code = EquipmentCode::all();

            $end_users = SupplyEndUser::with(['position', 'branch'])->get();
            $supply_emp = SupplyOfficeEmployee::with(['position'])->get();

            return view('so-dashboard.inventory-custodian-slip')
                ->with('ics_po', $ics_po)
                ->with('eq_code', $eq_code)
                ->with('end_users', $end_users)
                ->with('supply_emp', $supply_emp);
        } catch (\Exception $e) {
            return "HEY";
        }
    }
}
