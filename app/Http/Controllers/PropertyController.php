<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCode;
use App\Models\InventoryTransactionItemProperty;
use App\Models\SupplyEndUser;
use Illuminate\Http\Request;

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

        $eqCodesHighValue = EquipmentCode::with(['items' => function ($builder) {
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

        $eqCodesLowValue = EquipmentCode::with(['items' => function ($builder) {
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

        return view('so-dashboard.show-end-user')
            ->with('eqCodesHighValue', $eqCodesHighValue)
            ->with('eqCodesLowValue', $eqCodesLowValue)
            ->with('endUser', $endUser);
    }
}
