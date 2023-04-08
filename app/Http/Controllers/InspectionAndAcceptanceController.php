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
        $po_list = PurchaseOrder::with(['bac_reso', 'mop', 'mode_of_procurement'])
            ->whereHas('bac_reso', function ($query) {
                $query->where('year', '=', Auth::user()->ppmp_year);
            })
            ->doesntHave('iaa')
            ->where('is_delete', '=', 0)
            ->get();

        // return $po_list;
        return view('po-dashboard/add-iaa')
            ->with('po_list', $po_list);
    }

    public function post_new(Request $request)
    {
        $request->validate([
            'iar_number' => 'required',
            'iar_date' => 'required|date',
            'purchase_order' => 'required|exists:purchase_orders,id',
        ]);

        DB::beginTransaction();
        try {
            $new_iaa = new InspectionAndAcceptance();
            $new_iaa->iar_no = $request->iar_number;
            $new_iaa->purchase_orders_id = $request->purchase_order;
            // $new_iaa->branches_id = $request->branch;
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
        $iaa = InspectionAndAcceptance::with(['purchase_order'])->whereHas('purchase_order.bac_reso.abstract_of_canvass', function ($query) {
            $query->where('year', '=', Auth::user()->ppmp_year);
        })->get();

        return view('po-dashboard/all-iaa')
            ->with('iaa', $iaa);
    }

    public function view_single($ia_id)
    {
        $iaa = InspectionAndAcceptance::where('id', '=', $ia_id)
            ->with(['purchase_order'])
            ->get();

        if (count($iaa) === 0) {
            return redirect()->route('ia.all')->withErrors(['Invalid IAR.']);
        }

        return view('po-dashboard/view-iaa')
            ->with('iaa', $iaa);
    }
}
