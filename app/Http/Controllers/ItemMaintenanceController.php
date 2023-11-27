<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransactionItemProperty;
use App\Models\InventoryTransactionItemPropertyHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ItemMaintenanceController extends Controller
{
    public function item_maintenance_index()
    {
        session()->forget('selectedItems');

        $availableProperties = InventoryTransactionItemProperty::where('is_available', true)
            ->whereHas('item.equipment_code', function ($builder) {
                $builder->where('article', 'SEMI_EXPENDABLE');
            })
            ->get();

        $maintenanceProperties = InventoryTransactionItemProperty::has('histories')
            ->with([
                'histories' => function ($builder) {
                    $builder->orderBy('created_at', 'DESC')->where('type', 'MAINTENANCE')->get();
                },
                'item.equipment_code'
            ])
            ->whereHas('item.equipment_code', function ($builder) {
                $builder->where('article', 'SEMI_EXPENDABLE');
            })
            ->where('is_available', false)
            ->get();

        $groupedMaintenanceProperties = $maintenanceProperties->groupBy(function ($item) {
            return $item->histories[0]['record_number'];
        });

        return view('so-dashboard.item-maintenance')
            ->with('availableProperties', $availableProperties)
            ->with('maintenanceProperties', $maintenanceProperties)
            ->with('groupedMaintenanceProperties', $groupedMaintenanceProperties);
    }

    public function select_form(Request $request)
    {
        $selectedItems = InventoryTransactionItemProperty::whereIn('id', json_decode($request->selectedItems))
            ->with(['current_owners'])
            ->get();

        if (count($selectedItems) === 0) {
            return redirect()->back()->withErrors(['Invalid item IDs. Please try again.']);
        }

        $firstSupplyEndUsersId = $selectedItems->first()['current_owners'][0]['supply_end_users_id'];

        $allHaveSameSupplyEndUsersId = $selectedItems->every(function ($item) use ($firstSupplyEndUsersId) {
            return $item['current_owners'][0]['supply_end_users_id'] === $firstSupplyEndUsersId;
        });

        if (!$allHaveSameSupplyEndUsersId) {
            return redirect()->back()->withErrors(['Please select items under one owner/keeper.']);
        }

        session()->put('selectedItems', $selectedItems->pluck('id'));

        return view('so-dashboard.select-maintenance-form')
            ->with('selectedItems', $selectedItems);
    }

    public function maintenance_form()
    {
        $properties = InventoryTransactionItemProperty::whereIn('id', json_decode(session()->get('selectedItems')))
            ->where('is_available', true)
            ->get();

        if (count($properties) === 0) {
            return redirect()->route('so-dashboard.show')->withErrors(['Invalid selected properties.']);
        }

        return view('so-dashboard.maintenance-form')
            ->with('properties', $properties);
    }

    public function disposal_form($id)
    {
        $property = InventoryTransactionItemProperty::where('id', $id)
            ->where('is_available', true)
            ->first();

        if ($property === null) {
            return redirect()->route('so-dashboard.show')->withErrors(['Invalid property.']);
        }

        return view('so-dashboard.disposal-form')
            ->with('property', $property);
    }

    public function print_maintenance_request($id)
    {
        try {
            $printMaintenance = InventoryTransactionItemPropertyHistory::find($id);

            return view('so-dashboard.print-maintenance-request')
                ->with('printMaintenance', $printMaintenance);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Invalid request. Please try again.']);
        }
    }

    public function submit_maintenance_form(Request $request)
    {
        try {
            // $properties = InventoryTransactionItemProperty::whereIn('id', json_decode(session()->get('selectedItems')))
            //     ->where('is_available', true)
            //     ->get();

            if (count(array_diff($request->itemId, json_decode(session()->get('selectedItems')))) > 0) {
                return redirect()->back()->withErrors(['Invalid transaction. Please try again!']);
            }

            DB::beginTransaction();

            $yearNow = date('Y');

            $latest_rec = InventoryTransactionItemPropertyHistory::where(DB::raw('SUBSTR(record_number, 1, 4)'), '=', $yearNow)
                ->latest()
                ->first();

            $rec_number_ctr = $latest_rec === null ? 1 : intval(substr($latest_rec->record_number, 9, 4)) + 1;

            $rec_number = sprintf(
                '%s-%s-%s',
                $yearNow,
                date('m'),
                str_pad($rec_number_ctr, 3, '0', STR_PAD_LEFT),
            );

            for ($i = 0; $i < count($request->itemId); $i++) {
                $propertyDetail = InventoryTransactionItemProperty::where('id', $request->itemId[$i])
                    ->with(['item.transaction', 'item.bac_reso_item.quotation.pr_item.ppmp.item_detail', 'item.bac_reso_item.quotation.pr_item.ppmp.item_detail.unit', 'current_owners.end_user.branch', 'current_owners.end_user.position'])
                    ->first();

                $details = [
                    "cause_damage" => $request->cause_damage[$i],
                    "property_condition" => $request->property_condition[$i],
                    "remarks" => $request->remarks[$i],
                    "property_detail" => $propertyDetail,
                    "signatories" => [
                        "noted_by" => [
                            "name" => $request->noted_by,
                            "designation" => $request->designation,
                        ],
                        "verifier" => $request->verifier,
                    ],
                ];

                $newHistory = new InventoryTransactionItemPropertyHistory([
                    'inventory_transaction_item_properties_id' => $propertyDetail->id,
                    'type' => 'MAINTENANCE',
                    'record_number' => $rec_number,
                    'details' => json_encode($details),
                    'added_by' => Auth::user()->id,
                ]);
                $newHistory->save();

                InventoryTransactionItemProperty::where('id', $propertyDetail->id)
                    ->update(['is_available' => false, 'property_condition' => $request->property_condition[$i]]);
            }

            DB::commit();

            return redirect()->route('maintenance.index')->with('success', 'Successfully submitted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Invalid action.']);
        }
    }

    public function print_maintenance($rec_number, $mode)
    {
        $propertyHistories = InventoryTransactionItemPropertyHistory::where('record_number', $rec_number)
            ->with(['property.item.transaction'])
            ->get();

        if ($mode === "rrppe") {
            return view('so-dashboard.print-rrppe')
                ->with('propertyHistories', $propertyHistories);
        } elseif ($mode === "rssp") {
            $groupedPropertyHistories = $propertyHistories->groupBy(function ($item) {
                return $item->property->inventory_transaction_items_id;
            });

            return view('so-dashboard.print-rssp')
                ->with('groupedPropertyHistories', $groupedPropertyHistories);
        } else {
            return redirect()->back()->withErrors(['Invalaid action. Please try again.']);
        }
    }

    public function submit_disposal_form(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $property = InventoryTransactionItemProperty::where('id', $id)
                ->where('is_available', true)
                ->first();

            if ($property === null) {
                return redirect()->back()->withErrors(['Invalid property selected.']);
            }

            $details = [
                "cause_damage" => $request->cause_damage,
                "remarks" => $request->remarks,
            ];

            $yearNow = date('Y');

            $latest_rec = InventoryTransactionItemPropertyHistory::where(DB::raw('SUBSTR(record_number, 1, 4)'), '=', $yearNow)
                ->latest()
                ->first();

            $rec_number_ctr = $latest_rec === null ? 1 : intval(substr($latest_rec->record_number, 9, 4)) + 1;

            $rec_number = sprintf(
                '%s-%s-%s',
                $yearNow,
                date('m'),
                str_pad($rec_number_ctr, 3, '0', STR_PAD_LEFT),
            );

            $newHistory = new InventoryTransactionItemPropertyHistory([
                'inventory_transaction_item_properties_id' => $property->id,
                'type' => 'DISPOSE',
                'record_number' => $rec_number,
                'details' => json_encode($details),
                'added_by' => Auth::user()->id,
            ]);
            $newHistory->save();

            InventoryTransactionItemProperty::where('id', $property->id)
                ->update(['is_available' => false]);

            DB::rollBack();

            return $property;

            return redirect()->route('maintenance.index')->with('success', 'Successfully submitted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Invalid action.']);
        }
    }
}
