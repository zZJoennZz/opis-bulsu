<?php

namespace App\Http\Controllers;

use App\Models\BACResoItem;
use App\Models\EquipmentCode;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionIssuer;
use App\Models\InventoryTransactionItem;
use App\Models\InventoryTransactionItemProperty;
use App\Models\InventoryTransactionItemPropertyCurrentKeeper;
use App\Models\InventoryTransactionItemSerialNumber;
use App\Models\InventoryTransactionReceiver;
use App\Models\PurchaseOrder;
use App\Models\SupplyEndUser;
use App\Models\SupplyOfficeEmployee;
use App\Models\Branch;
use App\Models\FileAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function add_par()
    {
        try {
            $pos = PurchaseOrder::where('year', getPpmpYear())
                ->with(['bac_reso.bac_reso_items.quotation', 'company' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->whereHas('bac_reso.bac_reso_items.quotation', function ($builder) {
                    $builder->where('offered_unit_price', ">=", 50000);
                })
                ->get();

            $par_po = [];

            foreach ($pos as $po) {
                $itemCtr = 0;
                foreach ($po->transactions as $tran) {
                    // return $tran;
                    $itemCtr += count($tran->items);
                }
                if (count($po->bac_reso->bac_reso_items) !== $itemCtr) {
                    array_push($par_po, $po);
                }
            }

            $eq_code = EquipmentCode::all();

            $end_users = SupplyEndUser::with(['position', 'branch'])->get();
            $supply_emp = SupplyOfficeEmployee::with(['position'])->get();
            $branches = Branch::where('type', '<>', 'DEVELOPER')->get();

            return view('so-dashboard.property-acknowledgment-receipt')
                ->with('par_po', $par_po)
                ->with('eq_code', $eq_code)
                ->with('end_users', $end_users)
                ->with('branches', $branches)
                ->with('supply_emp', $supply_emp);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.show');
        }
    }

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

    public function save_par(Request $request)
    {
        $customMessages = [
            'purchaseOrderId.required' => 'Please select a purchase order.',
            'dateAcquired.required' => 'Please enter the date acquired.',
            'dateIssued.required' => 'Please enter the date issued.',
            'issuedBy.*.required' => 'Please select an issuer.',
            'issuedBy.*.exists' => 'Please select valid issuer.',
            'receivedBy.*.required' => 'Please select a receiver.',
            'receivedBy.*.exists' => 'Please select valid receiver.',
            'purchaseOrderItems.required' => 'Please provide purchase order items.',
            'purchaseOrderItems.*.equipmentCode.required' => 'Please select an equipment code for all purchase order items.',
            'purchaseOrderItems.*.serialNumbers.array' => 'Invalid value for serial numbers.',
            'branch' => 'Please select valid campus/office.',
        ];
        $request->validate([
            'purchaseOrderId' => 'required|exists:purchase_orders,id',
            'dateAcquired' => 'required',
            'dateIssued' => 'required',
            'issuedBy.*' => 'required|exists:supply_office_employees,id',
            'receivedBy.*' => 'required|exists:supply_end_users,id',
            'purchaseOrderItems' => 'required|array',
            'purchaseOrderItems.*.equipmentCode' => 'required',
            'purchaseOrderItems.*.serialNumbers' => 'array',
            'branch' => 'exists:branches,id',
        ], $customMessages);

        DB::beginTransaction();

        try {
            $branch = Branch::find($request->branch);

            //PAR number builder
            $latest_par = InventoryTransaction::where((DB::raw('YEAR(date_issued)')), date('Y', strtotime($request->dateIssued)))
                ->where('type', 'PAR')
                ->latest()
                ->first();

            $par_num_ctr = $latest_par === null ? 1 : intval(substr($latest_par->number, 9, 3)) + 1;
            $par_number = date('Y', strtotime($request->dateIssued)) . "-" . date('m', strtotime($request->dateIssued)) . "-" . str_pad($par_num_ctr, 3, '0', STR_PAD_LEFT);

            $new_par = new InventoryTransaction([
                "type" => "PAR",
                "number" => $par_number,
                "branches_id" => $branch->id,
                "date_acquired" => $request->dateAcquired,
                "purchase_orders_id" => $request->purchaseOrderId,
                "date_issued" => $request->dateIssued,
                "added_by" => Auth::user()->id,
            ]);

            $new_par->save();

            $poId = PurchaseOrder::find($request->purchaseOrderId);

            $po = PurchaseOrder::with([
                'bac_reso.bac_reso_items.quotation' => function ($builder) use ($poId) {
                    $builder->where('offered_unit_price', '>', '50000')
                        ->whereHas('quotation', function ($builder1) use ($poId) {
                            $builder1->where('companies_id', $poId->companies_id);
                        });
                },
                'bac_reso.bac_reso_items.quotation.quotation' => function ($builder) use ($poId) {
                    $builder->where('companies_id', $poId->companies_id);
                },
                'bac_reso.bac_reso_items.quotation.pr_item.ppmp.item_detail.unit',
                'bac_reso.bac_reso_items.quotation.pr_item.ppmp.milestones'
            ])
                ->where('id', $request->purchaseOrderId)
                ->first();

            $ctr = 0;
            foreach ($po->bac_reso->bac_reso_items as $item) {
                if ($item->quotation !== null) {
                    $eqCode = EquipmentCode::find($request->purchaseOrderItems[$ctr]['equipmentCode']);

                    $totalQty = 0;
                    foreach ($item->quotation->pr_item->ppmp->milestones as $m) {
                        $totalQty += $m->milestone_value;
                    }

                    $property_no = sprintf(
                        '%s-%s-%s-%s%s-%s',
                        date('Y', strtotime($request->dateIssued)),
                        date('m', strtotime($request->dateIssued)),
                        "14",
                        $eqCode->unique_code,
                        $eqCode->unique_code,
                        $branch->office_code,
                    );


                    $new_par_item = new InventoryTransactionItem([
                        "inventory_transactions_id" => $new_par->id,
                        "b_a_c_reso_items_id" => $item->id,
                        "quantity" => $totalQty,
                        "unit_price" => $item->quotation->offered_unit_price,
                        "equipment_codes_id" => $request->purchaseOrderItems[$ctr]['equipmentCode'],
                        "property_number" => $property_no,
                    ]);

                    $new_par_item->save();

                    if ($request->purchaseOrderItems[$ctr]['serialNumbers'] !== "" && $request->purchaseOrderItems[$ctr]['serialNumbers'] !== null) {
                        foreach ($request->purchaseOrderItems[$ctr]['serialNumbers'] as $sn) {
                            $new_property = new InventoryTransactionItemProperty([
                                "inventory_transaction_items_id" => $new_par_item->id,
                                "serial_number" => $sn,
                            ]);

                            $new_property->save();

                            foreach ($request->receivedBy as $receiver) {
                                $current_keeper = new InventoryTransactionItemPropertyCurrentKeeper([
                                    'inventory_transaction_item_properties_id' => $new_property->id,
                                    'supply_end_users_id' => $receiver,
                                ]);

                                $current_keeper->save();
                            }
                        }
                    } else {
                        for ($itemCtr = 0; $itemCtr <= $totalQty; $itemCtr++) {
                            $new_property = new InventoryTransactionItemProperty([
                                "inventory_transaction_items_id" => $new_par_item->id,
                                "serial_number" => "n/a",
                            ]);

                            foreach ($request->receivedBy as $receiver) {
                                $current_keeper = new InventoryTransactionItemPropertyCurrentKeeper([
                                    'inventory_transaction_item_properties_id' => $new_property->id,
                                    'supply_end_users_id' => $receiver,
                                ]);

                                $current_keeper->save();
                            }
                        }
                    }

                    $ctr += 1;
                }
            }

            foreach ($request->issuedBy as $issuer) {
                $new_issuer = new InventoryTransactionIssuer([
                    "inventory_transactions_id" => $new_par->id,
                    "supply_office_employees_id" => $issuer,
                ]);
                $new_issuer->save();
            }

            foreach ($request->receivedBy as $receiver) {
                $new_receiver = new InventoryTransactionReceiver([
                    "inventory_transactions_id" => $new_par->id,
                    "supply_end_users_id" => $receiver,
                ]);
                $new_receiver->save();
            }

            DB::commit();
            back()->with('success', 'Property acknowledgment receipt created.');

            return response()->json([
                'success' => true,
                'redirect' => route('so-dashboard.show'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Submission failed. Please try again or contact web developer.'
            ], 400);
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

        try {
            DB::beginTransaction();
            $branch = SupplyEndUser::with(['branch'])->where('id', $request->receivedBy)->first();

            //ics number builder
            $latest_ics = InventoryTransaction::where((DB::raw('YEAR(date_issued)')), date('Y', strtotime($request->dateIssued)))
                ->where('type', $type)
                ->latest()
                ->first();

            $ics_num_ctr = $latest_ics === null ? 1 : intval(substr($latest_ics->number, 9, 4)) + 1;

            $type_prefix = $type === "ICSL" ? "L" : "H";
            $ics_number = date('Y', strtotime($request->dateIssued)) . "-" . date('m', strtotime($request->dateIssued)) . "-" . $type_prefix . str_pad($ics_num_ctr, 3, '0', STR_PAD_LEFT);

            $new_ics = new InventoryTransaction([
                "type" => $type,
                "number" => $ics_number,
                "branches_id" => $branch->branch->id,
                "date_acquired" => $request->dateAcquired,
                "purchase_orders_id" => $request->purchaseOrderId,
                "date_issued" => $request->dateIssued,
                "added_by" => Auth::user()->id,
            ]);

            $new_ics->save();

            $poRecord = PurchaseOrder::find($request->purchaseOrderId);

            foreach ($request->purchaseOrderItems as $item) {
                $bacReso = BacResoItem::with(['quotation.quotation' => function ($builder) use ($poRecord) {
                    $builder->where('companies_id', $poRecord->companies_id);
                }, 'quotation.pr_item.ppmp.milestones'])->where('id', $item['itemId'])->first();

                if ($bacReso !== null && $bacReso->quotation->quotation !== null) {

                    //compute the total quantity of the item
                    $itemQty = 0;
                    foreach ($bacReso->quotation->pr_item->ppmp->milestones as $m) {
                        $itemQty += $m->milestone_value;
                    }

                    //store to database
                    $eqCode = EquipmentCode::find($item['equipmentCode']);
                    $property_no = sprintf(
                        '%s-%s-%s-%s',
                        date('Y', strtotime($request->dateIssued)),
                        "SE",
                        $eqCode->unique_code,
                        $branch->branch->office_code,
                    );
                    $new_ics_item = new InventoryTransactionItem([
                        "inventory_transactions_id" => $new_ics->id,
                        "b_a_c_reso_items_id" => $item['itemId'],
                        "quantity" => $itemQty,
                        "unit_price" => $bacReso->quotation->offered_unit_price,
                        "equipment_codes_id" => $item['equipmentCode'],
                        "property_number" => $property_no,
                    ]);

                    $new_ics_item->save();

                    for ($qtyCtr = 0; $qtyCtr < $itemQty; $qtyCtr++) {
                        $new_property = new InventoryTransactionItemProperty([
                            "inventory_transaction_items_id" => $new_ics_item->id,
                            "serial_number" => count($item['serialNumbers']) > 0 ? $item['serialNumbers'][$qtyCtr] : "n/a",
                        ]);

                        $new_property->save();

                        $current_keeper = new InventoryTransactionItemPropertyCurrentKeeper([
                            'inventory_transaction_item_properties_id' => $new_property->id,
                            'supply_end_users_id' => $request->receivedBy,
                        ]);

                        $current_keeper->save();
                    }
                } else {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid payload. Please try again.'
                    ], 400);
                }
            }

            // $po_id = PurchaseOrder::find($request->purchaseOrderId);
            // $withCond = "";
            // if ($type === "ICSL") {
            //     $withCond = [
            //         'bac_reso.bac_reso_items.quotation' => function ($builder) use ($po_id) {
            //             $builder->where('offered_unit_price', '<', '5000')
            //                 ->whereHas('quotation', function ($builder1) use ($po_id) {
            //                     $builder1->where('companies_id', $po_id->companies_id);
            //                 });
            //         },
            //         'bac_reso.bac_reso_items.quotation.quotation' => function ($builder) use ($po_id) {
            //             $builder->where('companies_id', $po_id->companies_id);
            //         },
            //         'bac_reso.bac_reso_items.quotation.pr_item.ppmp.item_detail.unit',
            //         'bac_reso.bac_reso_items.quotation.pr_item.ppmp.milestones'
            //     ];
            // } else {
            //     $withCond = [
            //         'bac_reso.bac_reso_items.quotation' => function ($builder) use ($po_id) {
            //             $builder->where('offered_unit_price', '<', '50000')
            //                 ->where('offered_unit_price', '>=', '5000')
            //                 ->whereHas('quotation', function ($builder1) use ($po_id) {
            //                     $builder1->where('companies_id', $po_id->companies_id);
            //                 });
            //         },
            //         'bac_reso.bac_reso_items.quotation.quotation' => function ($builder) use ($po_id) {
            //             $builder->where('companies_id', $po_id->companies_id);
            //         },
            //         'bac_reso.bac_reso_items.quotation.pr_item.ppmp.item_detail.unit',
            //         'bac_reso.bac_reso_items.quotation.pr_item.ppmp.milestones'
            //     ];
            // }
            // $po = PurchaseOrder::with($withCond)
            //     ->where('id', $request->purchaseOrderId)
            //     ->first();
            // $ctr = 0;
            // foreach ($po->bac_reso->bac_reso_items as $item) {
            //     if ($item->quotation !== null) {
            //         // return $item->quotation->quotation;
            //         $eqCode = EquipmentCode::find($request->purchaseOrderItems[$ctr]['equipmentCode']);

            //         $totalQty = 0;
            //         foreach ($item->quotation->pr_item->ppmp->milestones as $m) {
            //             $totalQty += $m->milestone_value;
            //         }

            //         $property_no = sprintf(
            //             '%s-%s-%s-%s',
            //             date('Y', strtotime($request->dateIssued)),
            //             "SE",
            //             $eqCode->unique_code,
            //             $branch->branch->office_code,
            //         );


            //         $new_ics_item = new InventoryTransactionItem([
            //             "inventory_transactions_id" => $new_ics->id,
            //             "b_a_c_reso_items_id" => $item->id,
            //             "quantity" => $totalQty,
            //             "unit_price" => $item->quotation->offered_unit_price,
            //             "equipment_codes_id" => $request->purchaseOrderItems[$ctr]['equipmentCode'],
            //             "property_number" => $property_no,
            //         ]);

            //         $new_ics_item->save();

            //         if (isset($request->purchaseOrderItems[$ctr]['serialNumbers']) && is_array($request->purchaseOrderItems[$ctr]['serialNumbers'])) {
            //             foreach ($request->purchaseOrderItems[$ctr]['serialNumbers'] as $sn) {
            //                 $new_property = new InventoryTransactionItemProperty([
            //                     "inventory_transaction_items_id" => $new_ics_item->id,
            //                     "serial_number" => $sn,
            //                 ]);

            //                 $new_property->save();

            //                 $current_keeper = new InventoryTransactionItemPropertyCurrentKeeper([
            //                     'inventory_transaction_item_properties_id' => $new_property->id,
            //                     'supply_end_users_id' => $request->receivedBy,
            //                 ]);

            //                 $current_keeper->save();
            //             }
            //         } else {
            //             for ($itemCtr = 0; $itemCtr < $totalQty; $itemCtr++) {
            //                 $new_property = new InventoryTransactionItemProperty([
            //                     "inventory_transaction_items_id" => $new_ics_item->id,
            //                     "serial_number" => "n/a",
            //                 ]);

            //                 $current_keeper = new InventoryTransactionItemPropertyCurrentKeeper([
            //                     'inventory_transaction_item_properties_id' => $new_property->id,
            //                     'supply_end_users_id' => $request->receivedBy,
            //                 ]);

            //                 $current_keeper->save();
            //             }
            //         }

            //         $ctr += 1;
            //     }
            // }

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
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ], 400);
        }
    }

    public function all_trans()
    {
        try {
            $allTrans = InventoryTransaction::with(['purchase_order', 'issuers', 'receivers'])
                ->get();
            return view('so-dashboard.all-transactions')
                ->with('allTrans', $allTrans);
        } catch (\Exception $e) {
            return redirect()->route('so-dashboard.show')->withErrors(['Something went wrong. Cannot access transactions.']);
        }
    }

    public function view_transaction($id)
    {
        $transaction = InventoryTransaction::find($id);
        $branches = Branch::all();
        return view('so-dashboard.view-transaction')
            ->with('transaction', $transaction)
            ->with('branches', $branches);
    }

    public function print_ics($id)
    {
        try {
            $icsRecord = InventoryTransaction::find($id);
            return view('so-dashboard.print-ics')
                ->with('icsRecord', $icsRecord);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            throw $th;
        }
    }

    public function print_par($id)
    {
        try {
            $parRecord = InventoryTransaction::find($id);
            return view('so-dashboard.print-par')
                ->with('parRecord', $parRecord);
        } catch (\Throwable $th) {
            error_log($th->getMessage());
            throw $th;
        }
    }

    public function attach_file(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $transaction = InventoryTransaction::find($id);

            $request->validate([
                'file_attachment' => 'required|file|mimes:pdf,jpg,png',
            ]);

            $attachment = $request->file('file_attachment');

            $newFileName = Str::uuid() . ' - ' . $transaction->number . '.' . $attachment->getClientOriginalExtension();

            $attachment->storeAs('public/attachments', $newFileName);

            // return asset('storage/attachments/' . $newFileName);

            $newFileAttachment = new FileAttachment();
            $newFileAttachment->file_name = $newFileName;
            $newFileAttachment->save();

            if ($transaction->file_attachments_id === null) {
                $transaction->file_attachments_id = json_encode([$newFileAttachment->id]);
            } else {
                $currFileAttachments = json_decode($transaction->file_attachments_id);
                array_push($currFileAttachments, $newFileAttachment->id);
                $transaction->file_attachments_id = json_encode($currFileAttachments);
            }
            $transaction->save();

            DB::commit();

            return redirect()->back()->with('success', 'File successfully uploaded.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors(['File upload failed.']);
        }
    }

    public function delete_attachment($itemId, $tranId)
    {
        try {
            DB::beginTransaction();

            // Find the FileAttachment record by ID
            $fileAttachment = FileAttachment::find($itemId);

            if (!$fileAttachment) {
                return redirect()->back()->withErrors(['File attachment not found.']);
            }

            // Get the file path for the attachment
            $filePath = storage_path('app/public/attachments/' . $fileAttachment->file_name);

            // Check if the file exists and delete it
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete the FileAttachment record from the database
            $fileAttachment->delete();

            // Find the InventoryTransaction by ID
            $transaction = InventoryTransaction::find($tranId);

            if (!$transaction) {
                return redirect()->back()->withErrors(['Transaction not found.']);
            }

            // Remove the deleted ID from the InventoryTransaction's file_attachments_id
            $fileAttachments = json_decode($transaction->file_attachments_id);
            $fileAttachments = array_diff($fileAttachments, [$itemId]);
            if (count($fileAttachments) > 0) {
                $transaction->file_attachments_id = json_encode(array_values($fileAttachments));
            } else {
                $transaction->file_attachments_id = null;
            }
            $transaction->save();

            DB::commit();

            return redirect()->back()->with('success', 'File successfully deleted.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors(['File not deleted.']);
        }
    }
}
