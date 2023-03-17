<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InspectionAndAcceptance;
use App\Models\SourceOfFund;
use App\Models\SupplyOfficeEmployee;
use App\Models\SupplyEndUser;

class InventoryCustodianController extends Controller
{
    //
    public function show()
    {
        $iaas = InspectionAndAcceptance::with(['purchase_order.canvass_abstract.items.quotation_item.pr_item.ppmp.milestones', 'purchase_order.canvass_abstract.items.quotation_item.pr_item.ppmp.item_detail.unit'])
            ->get();
        $supply_employees = SupplyOfficeEmployee::with('position')->get();
        $end_users = SupplyEndUser::with(['position', 'branch'])->get();
        $source_of_funds = SourceOfFund::all();
        return view('so-dashboard/ic-form')
            ->with('iaas', $iaas)
            ->with('supply_employees', $supply_employees)
            ->with('end_users', $end_users)
            ->with('source_of_funds', $source_of_funds);
    }
}
