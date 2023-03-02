<?php

namespace App\Http\Controllers;

use App\Models\CanvassAbstract;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderModeOfPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PurchaseOrderController extends Controller
{
    //
    public function get_all()
    {
        $po_list = PurchaseOrder::with(['canvass_abstract.company', 'mop'])
            ->whereHas('canvass_abstract', function ($query) {
                $query->where('year', '=', Auth::user()->ppmp_year);
            })
            ->where('is_delete', '=', 0)
            ->get();
        // return $po_list;
        return view('po-dashboard/purchase-order-list')->with('po_list', $po_list);
    }

    public function add_new()
    {
        $bac_reso = CanvassAbstract::with(['company'])
            ->where('is_delete', '=', 0)
            ->doesntHave('purchase_order')
            ->get();
        // return $bac_reso;
        $mode_of_payment = PurchaseOrderModeOfPayment::all();
        return view('po-dashboard/new-purchase-order')
            ->with('bac_reso', $bac_reso)
            ->with('mode_of_payment', $mode_of_payment);
    }

    public function generate_po(Request $request)
    {
        $request->validate([
            'bac_reso' => 'numeric|exists:canvass_abstracts,id',
            'mop' => 'numeric|exists:purchase_order_mode_of_payments,id',
        ]);

        DB::beginTransaction();
        try {
            $new_po = new PurchaseOrder();
            $new_po->canvass_abstracts_id = $request->bac_reso;
            $new_po->purchase_order_mode_of_payments_id = $request->mop;
            $new_po->po_number = 1;
            $new_po->added_by = Auth::user()->id;
            $new_po->save();

            $last_po = PurchaseOrder::find($new_po->id);
            $po_num_hash = "PO" . crc32($new_po->id);
            $last_po->po_number = $po_num_hash;
            $last_po->save();

            DB::commit();
            back()->with('success', 'Purchase order generated.');
            return response()->json([
                'success' => true,
                'message' => 'Purchase order generated.'
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            back()->withErrors(['Something went wrong. Purchase order not generated.']);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Purchase order not generated.'
            ], 400);
        }
    }

    public function view_po($po_id)
    {
        $po = PurchaseOrder::where('id', '=', $po_id)
            ->with(['canvass_abstract.company', 'mop', 'canvass_abstract.items.quotation_item.pr_item.ppmp.milestones', 'canvass_abstract.items.quotation_item.pr_item.ppmp.item_detail.unit'])
            ->first();
        // return $po;
        return view('po-dashboard/view-po')->with('po', $po);
    }
}
