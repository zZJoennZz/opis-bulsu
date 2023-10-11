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
        $availableProperties = InventoryTransactionItemProperty::where('is_available', true)
            ->whereHas('item.equipment_code', function ($builder) {
                $builder->where('article', 'NON_SEMI_EXPENDABLE');
            })
            ->get();

        $maintenanceProperties = InventoryTransactionItemProperty::has('histories')
            ->with([
                'histories' => function ($builder) {
                    $builder->orderBy('created_at', 'DESC')->get();
                }
            ])
            ->whereHas('item.equipment_code', function ($builder) {
                $builder->where('article', 'NON_SEMI_EXPENDABLE');
            })
            ->where('is_available', false)
            ->get();

        return view('so-dashboard.item-maintenance')
            ->with('availableProperties', $availableProperties)
            ->with('maintenanceProperties', $maintenanceProperties);
    }

    public function select_form($id)
    {
        return view('so-dashboard.select-maintenance-form')
            ->with('propertyId', $id);
    }

    public function maintenance_form($id)
    {
        $property = InventoryTransactionItemProperty::where('id', $id)
            ->where('is_available', true)
            ->first();

        if ($property === null) {
            return redirect()->route('so-dashboard.show')->withErrors(['Invalid property.']);
        }

        return view('so-dashboard.maintenance-form')
            ->with('property', $property);
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

    public function submit_maintenance_form(Request $request, $id)
    {
        try {
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

            $newHistory = new InventoryTransactionItemPropertyHistory([
                'inventory_transaction_item_properties_id' => $property->id,
                'type' => 'MAINTENANCE',
                'details' => json_encode($details),
                'added_by' => Auth::user()->id,
            ]);
            $newHistory->save();

            InventoryTransactionItemProperty::where('id', $property->id)
                ->update(['is_available' => false]);

            DB::commit();

            return redirect()->route('maintenance.index')->with('success', 'Successfully submitted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Invalid action.']);
        }
    }

    public function submit_disposal_form(Request $request, $id)
    {
        try {
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

            $newHistory = new InventoryTransactionItemPropertyHistory([
                'inventory_transaction_item_properties_id' => $property->id,
                'type' => 'DISPOSE',
                'details' => json_encode($details),
                'added_by' => Auth::user()->id,
            ]);
            $newHistory->save();

            InventoryTransactionItemProperty::where('id', $property->id)
                ->update(['is_available' => false]);

            DB::commit();

            return redirect()->route('maintenance.index')->with('success', 'Successfully submitted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Invalid action.']);
        }
    }
}
