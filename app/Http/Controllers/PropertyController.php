<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransactionItemProperty;
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
}
