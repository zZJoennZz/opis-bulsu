<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class QuotationController extends Controller
{
    public function all()
    {
        $quotations = Quotation::with(['items', 'company', 'items.ppmp', 'items.ppmp.milestones'])->where('year', '=', getPpmpYear())->get();
        $pryears = PurchaseRequest::where('year', '=', getPpmpYear())->get();
        // return $quotations;
        // return view('po-dashboard/quotation-list')->with('quotations', $quotations);
        return view('po-dashboard.quotation-list', compact('quotations', 'pryears'));
    }

    public function add()
    {
        $company_profiles = Company::all();
        // $quotation_items = QuotationItem::select('pro_pro_man_plans_id')
        //     ->whereIn('quotations_id', function ($query) {
        //         $query->select('id')->from('quotations')->where('year', '=', Auth::user()->ppmp_year);
        //     })->get();
        $pr_items = PurchaseRequestItem::select('purchase_requests_id')
            ->whereIn('purchase_requests_id', function ($query) {
                $query->select('id')->from('purchase_requests')->where('year', '=', Auth::user()->ppmp_year);
            })->get();
        $purchase_requests = PurchaseRequest::where('year', '=', Auth::user()->ppmp_year)
            ->where('is_approve', '=', 1)
            ->where('is_delete', '=', 0)
            ->where('is_draft', '=', 0)
            ->whereIn('id', $pr_items)
            ->get();

        return view('po-dashboard/add-new-quotation')
            ->with('company_profiles', $company_profiles)
            ->with('purchase_requests', $purchase_requests);
    }

    public function new_request(Request $request)
    {
        DB::beginTransaction();
        try {
            $new_quotation = new Quotation();
            $new_quotation->companies_id = $request->companies_id;
            $new_quotation->quotation_number = "na";
            $new_quotation->year = getPpmpYear();
            $new_quotation->save();
            $quote_id = $new_quotation->id;
            $find_quotation = Quotation::find($quote_id);
            $find_quotation->quotation_number = "Q" . crc32($quote_id);
            $find_quotation->save();
            foreach ($request->items as $item) {
                $new_quotation_item = new QuotationItem();
                $new_quotation_item->item_number = $item['item_number'];
                $new_quotation_item->quotations_id = $quote_id;
                $ppmp = PurchaseRequestItem::where('id', '=', $item['purchase_requests_id'])->get();
                $new_quotation_item->pro_pro_man_plans_id = $ppmp[0]->pro_pro_man_plans_id;
                $new_quotation_item->brand_and_model_offered = $item['brand_and_model_offered'];
                $new_quotation_item->offered_unit_price = $item['offered_unit_price'];
                $new_quotation_item->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Quotation request submitted.'
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Quotation request failed to submit.'
            ], 400);
        }
    }

    public function get_single($quotation_id)
    {
        try {
            $quotation = Quotation::where('id', '=', $quotation_id)
                ->with(['items', 'items.ppmp', 'items.ppmp.item_detail', 'items.ppmp.item_detail.unit', 'items.ppmp.item_purpose', 'items.ppmp.pr_item', 'items.ppmp.pr_item.pr', 'items.ppmp.milestones', 'company'])
                ->get();
            if (count($quotation) === 0) {
                return redirect()->route('quotation.all');
            }
            return response()->json([
                'success' => true,
                'data' => $quotation,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Can\'t fetch quotation.'
            ], 400);
        }
    }

    public function get_summary()
    {
        $quotation_summaries = Company::whereHas('quotations.items.ppmp', function ($query) {
            $query->where('year', '=', getPpmpYear());
        })
            ->with('quotations', function ($query) {
                // return $query->leftJoin('quotation_items', 'quotation_items.quotations_id', '=', 'quotations.id')->leftJoin('pro_pro_man_plans', 'pro_pro_man_plans.id', '=', 'quotation_items.pro_pro_man_plans_id')->where('pro_pro_man_plans.year', '=', getPpmpYear());
                return $query->where('year', '=', getPpmpYear());
            })
            ->with('quotations.items', function ($query) {
                return $query->leftJoin('pro_pro_man_plans', 'quotation_items.pro_pro_man_plans_id', '=', 'pro_pro_man_plans.id')->where('pro_pro_man_plans.year', '=', getPpmpYear());
            })
            ->with('quotations.items.ppmp', function ($query) {
                return $query->where('pro_pro_man_plans.year', '=', getPpmpYear())->with('item_detail');
            })
            ->get();
        // return $quotation_summaries;
        return view('po-dashboard/quotation-summary')->with('quotation_summaries', $quotation_summaries);
    }

    public function get_company_quotations($company_id)
    {

        try {
            $company_quotations = Company::where('id', '=', $company_id)->whereHas('quotations', function ($query) {
                $query->where('year', '=', Auth::user()->ppmp_year);
            })->with('quotations', 'quotations.items', 'quotations.items.ppmp', 'quotations.items.ppmp.item_detail', 'quotations.items.ppmp.item_detail.unit')->get();

            if (count($company_quotations) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $company_quotations
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No records found for this company.'
                ], 404);
            }
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Cannot process your request.'
            ], 500);
        }
    }
}
