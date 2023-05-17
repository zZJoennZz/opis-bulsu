<?php

namespace App\Http\Controllers;

use App\Models\AllotAndOblSlip;
use App\Models\BACReso;
use App\Models\BACResoItem;
use App\Models\Company;
use App\Models\InspectionAndAcceptance;
use App\Models\ModeOfProcurement;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderModeOfPayment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PurchaseOrderController extends Controller
{
    //
    public function get_all()
    {
        $po_list = PurchaseOrder::whereHas('bac_reso.abstract_of_canvass', function ($builder) {
            $builder->where('year', '=', getPpmpYear());
        })
            ->with(['mop'])
            ->where('is_delete', '=', 0)
            ->latest()
            ->get();
        // return $po_list;
        return view('po-dashboard/purchase-order-list')->with('po_list', $po_list);
    }

    public function add_new()
    {

        $bac_reso = BACReso::with(['purchase_order'])
            ->where('is_delete', '=', 0)
            ->where('is_draft', '=', 0)
            ->get();

        // return $bac_reso;
        $mode_of_payment = PurchaseOrderModeOfPayment::all();
        $mode_of_procurement = ModeOfProcurement::all();
        return view('po-dashboard/new-purchase-order')
            ->with('bac_reso', $bac_reso)
            ->with('mode_of_procurement', $mode_of_procurement)
            ->with('mode_of_payment', $mode_of_payment);
    }

    public function generate_po(Request $request)
    {
        $request->validate([
            'bac_reso' => 'required|numeric|exists:b_a_c_resos,id',
            'company' => 'required|numeric|exists:companies,id',
            'mode_of_procurement' => 'required|numeric|exists:mode_of_procurements,id',
            'mode_of_payment' => 'required|numeric|exists:purchase_order_mode_of_payments,id',
            'place_of_delivery' => 'required',
            'date_of_delivery' => 'required',
            'for_inquiry' => 'required',
            'delivery_term' => 'required',
            'accountant_name' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $new_po = new PurchaseOrder();
            $new_po->b_a_c_resos_id = $request->bac_reso;
            $new_po->companies_id = $request->company;
            $new_po->year = getPpmpYear();
            $new_po->purchase_order_mode_of_payments_id = $request->mode_of_payment;
            $new_po->mode_of_procurements_id  = $request->mode_of_procurement;
            $new_po->place_of_delivery  = $request->place_of_delivery;
            $new_po->date_of_delivery  = $request->date_of_delivery;
            $new_po->for_inquiry  = $request->for_inquiry;
            $new_po->delivery_term  = $request->delivery_term;
            $new_po->accountant_name  = $request->accountant_name;
            $new_po->added_by = Auth::user()->id;

            $latest_po = PurchaseOrder::where('year', getPpmpYear())
                ->latest()
                ->first();

            $po_num_ctr = $latest_po === null ? 1 : intval(substr($latest_po->po_number, 8, 4)) + 1;

            $po_number = sprintf(
                '%s-%s-%s',
                getPpmpYear(),
                date('m'),
                str_pad($po_num_ctr, 4, '0', STR_PAD_LEFT)
            );

            $new_po->po_number = $po_number;

            $new_po->save();

            $get_budget = User::where('account_type', 'BUDGET_OFFICE')->first();

            $new_alobs = new AllotAndOblSlip();
            $new_alobs->purchase_orders_id = $new_po->id;
            $new_alobs->budget_officer_id = $get_budget->id;
            $new_alobs->added_by = Auth::user()->id;

            $new_alobs->save();

            $new_iaa = new InspectionAndAcceptance();
            $new_iaa->iar_no = "";
            $new_iaa->purchase_orders_id = $new_po->id;
            $new_iaa->iar_date = date('Y-m-d');
            $new_iaa->responsibility_center_code = "";
            $new_iaa->added_by = Auth::user()->id;
            $new_iaa->save();

            DB::commit();
            back()->with('success', 'Your purchase order has been successfully generated and ALOBS and IAR have been created. To proceed further, kindly complete the required forms in their respective modules.');
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
        try {
            $company = PurchaseOrder::select('companies_id')->where('id', '=', $po_id)->first();

            // return $company;
            $po = PurchaseOrder::where('id', '=', $po_id)
                ->with(['mode_of_procurement', 'mop', 'bac_reso.abstract_of_canvass.pr', 'bac_reso.bac_reso_items.quotation.quotation' => function ($builder) use ($company) {
                    $builder->where('companies_id', '=', $company->companies_id);
                }, 'company'])
                ->first();
            // return $po;
            if ($po === null) {
                return redirect()->route('po.all')->withErrors(['No purchase order found.']);
            }
            return view('po-dashboard/print-purchase-order')->with('po', $po);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Something went wrong. Please try again or contact web developer.']);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $po = PurchaseOrder::where('id', $id)
                ->where('year', getPpmpYear())
                ->first();
            if ($po === null) {
                return redirect()->back()->withErrors(['Invalid PO record.']);
            }

            InspectionAndAcceptance::where('purchase_orders_id', $id)->delete();
            AllotAndOblSlip::where('purchase_orders_id', $id)->delete();
            PurchaseOrder::where('id', $id)->delete();

            DB::commit();
            return redirect()->route('po.all')->with('success', 'The purchase order, along with all related ALOBS and Inspection and Acceptance reports, have been successfully deleted from the system.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. Please try again or contact web developer.']);
        }
    }

    public function get_by_iar($iar_id)
    {
        try {
            $iar = InspectionAndAcceptance::find($iar_id);
            $get_items = BACResoItem::where('b_a_c_resos_id', $iar->purchase_order->b_a_c_resos_id)
                ->with(['quotation.pr_item.ppmp.item_detail', 'bac_reso.purchase_order.company'])
                ->get();

            return response()->json([
                'success' => true,
                'data' => $get_items
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Cannot fetch items.'
            ], 400);
        }
    }
}
