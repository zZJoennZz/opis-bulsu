<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCode;
use App\Models\InventoryTransactionItemProperty;
use App\Models\SupplyEndUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    //
    public function list()
    {
        try {
            $items = InventoryTransactionItemProperty::with(['transfers' => function ($builder) {
                $builder->latest();
            }, 'current_owners.end_user', 'item.transaction', 'item.bac_reso_item.quotation.pr_item.ppmp.item_detail'])
                ->where('is_available', true)
                ->get();

            return view('so-dashboard.all-properties')
                ->with('items', $items);
        } catch (\Exception $e) {
            // return $e;
            return redirect()->to('/')->withErrors(['Cannot get records. Please try again later or contact web developer.']);
        }
    }

    public function view($propertyId)
    {
        $item = InventoryTransactionItemProperty::with(['transfers' => function ($builder) {
            $builder->latest();
        }, 'transfers.transfer' => function ($builder) {
            $builder->latest();
        }])->find($propertyId);

        if ($item === null) {
            return redirect()->to('dashboard')->withErrors(['Invalid record.']);
        }

        return view('so-dashboard.view-property')
            ->with('item', $item);
    }

    public function users()
    {
        $allEndUsers = SupplyEndUser::has('keepers')->with(['position', 'branch', 'keepers.item.item'])->get();

        return view('so-dashboard.all-end-users')
            ->with('allEndUsers', $allEndUsers);
    }

    public function user_items($userId)
    {
        $endUser = SupplyEndUser::find($userId);

        $eqCodesHighValue = $this->getEqCodesHighValue($userId);
        $eqCodesLowValue = $this->getEqCodesLowValue($userId);

        return view('so-dashboard.show-end-user')
            ->with('eqCodesHighValue', $eqCodesHighValue)
            ->with('eqCodesLowValue', $eqCodesLowValue)
            ->with('endUser', $endUser);
    }

    public function print_user_items_rpcsp($userId)
    {
        $endUser = SupplyEndUser::find($userId);

        $eqCodesHighValue = $this->getEqCodesHighValue($userId);
        $eqCodesLowValue = $this->getEqCodesLowValue($userId);

        return view('so-dashboard.print-rpcsp')
            ->with('eqCodesHighValue', $eqCodesHighValue)
            ->with('eqCodesLowValue', $eqCodesLowValue)
            ->with('endUser', $endUser);
    }

    private function getEqCodesHighValue($userId)
    {
        return EquipmentCode::with(['items' => function ($builder) {
            $builder->whereBetween('unit_price', [5000, 49999.99])->orderBy('unit_price', 'DESC');
        }, 'items.properties' => function ($builder) use ($userId) {
            $builder->whereHas('current_owners', function ($builder1) use ($userId) {
                $builder1->where('supply_end_users_id', $userId);
            });
        }, 'items.bac_reso_item.quotation.pr_item.ppmp.item_detail.unit'])->whereHas('items', function ($builder) use ($userId) {
            $builder->whereBetween('unit_price', [5000, 49999.99])->whereHas('properties.current_owners', function ($builder1) use ($userId) {
                $builder1->where('supply_end_users_id', $userId);
            });
        })
            ->get();
    }

    private function getEqCodesLowValue($userId)
    {
        return EquipmentCode::with(['items' => function ($builder) {
            $builder->whereBetween('unit_price', [0, 4999.99])->orderBy('unit_price', 'DESC');
        }, 'items.properties' => function ($builder) use ($userId) {
            $builder->whereHas('current_owners', function ($builder1) use ($userId) {
                $builder1->where('supply_end_users_id', $userId);
            });
        }, 'items.bac_reso_item.quotation.pr_item.ppmp.item_detail.unit'])->whereHas('items', function ($builder) use ($userId) {
            $builder->whereBetween('unit_price', [0, 4999.99])->whereHas('properties.current_owners', function ($builder1) use ($userId) {
                $builder1->where('supply_end_users_id', $userId);
            });
        })
            ->get();
    }

    public function edit(Request $request)
    {
        session()->forget('propertyId');
        $property = InventoryTransactionItemProperty::find($request->propertyId);

        if ($property === null) {
            return redirect()->back()->withErrors(['Invalid property ID.']);
        }

        session()->put('propertyId', $request->propertyId);

        return view('so-dashboard.edit-property-information')
            ->with('property', $property);
    }

    public function update(Request $request)
    {
        try {
            $property = InventoryTransactionItemProperty::find(session()->get('propertyId'));

            if ($property === null) {
                return redirect()->back()->withErrors(['Invalid property selected.']);
            }

            DB::beginTransaction();
            $property->property_condition = $request->property_condition;
            $property->accumulated_depreciation = $request->accumulated_depreciation;
            $property->accumulated_impairment_losses = $request->accumulated_impairment_losses;
            $property->carrying_amount = $request->carrying_amount;
            $property->save();
            DB::commit();

            return redirect()->to('/inventory-and-inspection-report-of-unserviceable-property')->with('success', 'Property successfully updated!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->to('/inventory-and-inspection-report-of-unserviceable-property')->withErrors(['Something went wrong! Please try again.']);
        }
    }
}
