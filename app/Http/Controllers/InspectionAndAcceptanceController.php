<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\InspectionAndAcceptance;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InspectionAndAcceptanceController extends Controller
{
    //
    public function add_new()
    {
        $po_list = PurchaseOrder::with(['canvass_abstract.company', 'mop'])
            ->whereHas('canvass_abstract', function ($query) {
                $query->where('year', '=', Auth::user()->ppmp_year);
            })
            ->doesntHave('iaa')
            ->where('is_delete', '=', 0)
            ->get();

        $branches = Branch::all();

        // return $po_list;
        return view('po-dashboard/add-iaa')
            ->with('po_list', $po_list)
            ->with('branches', $branches);
    }

    public function post_new(Request $request)
    {
        $request->validate([
            'iar_number' => 'required',
            'iar_date' => 'required|date',
            'purchase_order' => 'required|exists:purchase_orders,id',
            'branch' => 'required|exists:branches,id',
            'rcc' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $new_iaa = new InspectionAndAcceptance();
            $new_iaa->iar_no = $request->iar_number;
            $new_iaa->purchase_orders_id = $request->purchase_order;
            $new_iaa->branches_id = $request->branch;
            $new_iaa->iar_date = $request->iar_date;
            $new_iaa->responsibility_center_code = $request->rcc;
            $new_iaa->added_by = Auth::user()->id;
            $new_iaa->save();
            DB::commit();

            return redirect()->route('ia.all')->with('success', 'IAR Report saved!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong with the database! Please contact web administrator.']);
        }
    }

    public function all()
    {
        $iaa = InspectionAndAcceptance::with(['branch', 'purchase_order.canvass_abstract.company'])->whereHas('purchase_order.canvass_abstract', function ($query) {
            $query->where('year', '=', Auth::user()->ppmp_year);
        })->get();

        return view('po-dashboard/all-iaa')
            ->with('iaa', $iaa);
    }

    public function view_single($ia_id)
    {
        $iaa = InspectionAndAcceptance::where('id', '=', $ia_id)
            ->with(['branch', 'purchase_order.canvass_abstract.company', 'purchase_order.canvass_abstract.items.quotation_item.pr_item.ppmp.item_detail.unit', 'purchase_order.canvass_abstract.items.quotation_item.pr_item.ppmp.milestones'])
            ->get();

        if (count($iaa) === 0) {
            return redirect()->route('ia.all')->withErrors(['Invalid IAR.']);
        }

        return view('po-dashboard/view-iaa')
            ->with('iaa', $iaa);
    }
}
