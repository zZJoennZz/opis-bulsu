<?php

namespace App\Http\Controllers;

use App\Models\ProProManPlan;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\Request;
use App\Models\PurchaseRequestMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PurchaseRequestController extends Controller
{
    public function pr_admin()
    {
        return view('po-dashboard/view-purchase-request');
    }

    public function pr_list()
    {
        $user = Auth::user();

        $pr_records = [];
        $is_enabled = $this->isPrEnabled();
        $return_value = view('dashboard/purchase_request_list')->with('is_pr_enabled', $is_enabled);
        if ($is_enabled) {
            $pr_records = PurchaseRequest::with(['pr_items', 'pr_items.ppmp' => function ($query) use ($user) {
                return $query->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1)->where('is_consolidate', '=', 1)->where('is_delete', '=', 0)->where('year', '=', $user->ppmp_year)->where('branches_id', '=', $user->branches_id)->with('item_detail');
            }])->with('branch')->with('requester')->get();
            $return_value = $return_value->with('pr_records', $pr_records);
        }
        return $return_value;
    }

    public function pr_form()
    {
        return view('dashboard/purchase_request_form')->with('is_pr_enabled', $this->isPrEnabled());
    }

    public function pr_available_items_api()
    {
        try {
            $available_items = ProProManPlan::where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1)->where('is_consolidate', '=', 1)->where('is_delete', '=', 0)->where('year', '=', Auth::user()->ppmp_year)->where('branches_id', '=', Auth::user()->branches_id)->whereNotIn('id', function ($query) {
                return $query->select('pro_pro_man_plans_id')->from('purchase_request_items');
            })->with('item_detail')->with('milestones')->with('source_of_fund')->with('item_purpose')->get();

            return response()->json([
                'success' => true,
                'data' => $available_items
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please refresh the page. If the problem persists, contact website administrator.'
            ], 400);
        }
    }

    public function new_submission(Request $request)
    {
        $ppmp_id = json_decode($request->id);
        $user = Auth::user();

        //2nd layer of validation
        $is_ppmp_valid_for_pr = count(ProProManPlan::whereIn('id', $ppmp_id)->where('branches_id', '=', $user->branches_id)->where('year', '=', $user->ppmp_year)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1)->where('is_consolidate', '=', 1)->where('is_delete', '=', 0)->whereNotIn('id', function ($query) {
            return $query->select('pro_pro_man_plans_id')->from('purchase_request_items');
        })->get()) === 0 ? false : true;

        DB::beginTransaction();
        if ($is_ppmp_valid_for_pr) {
            $new_purchase_request = new PurchaseRequest();
            $new_purchase_request->year = $user->ppmp_year;
            $new_purchase_request->pr_number = 1;
            $new_purchase_request->is_draft = 0;
            $new_purchase_request->is_approve = 0;
            $new_purchase_request->is_delete = 0;
            $new_purchase_request->branches_id = $user->branches_id;
            $new_purchase_request->requested_by = $user->id;
            $new_purchase_request->save();

            $pr_num_hash = "PR" . crc32($new_purchase_request->id);
            $store_pr_num = PurchaseRequest::find($new_purchase_request->id);
            $store_pr_num->pr_number = $pr_num_hash;
            $store_pr_num->save();

            foreach ($ppmp_id as $ppmp) {
                $pr_item = new PurchaseRequestItem();
                $pr_item->pro_pro_man_plans_id = $ppmp;
                $pr_item->purchase_requests_id = $new_purchase_request->id;
                $pr_item->save();
            }

            DB::commit();
            back()->with('success', 'Purchase request successfully submitted.');
            return response()->json([
                'success' => true,
                'message' => 'Purchase request submitted.'
            ], 200);
        } else {
            DB::rollBack();
            back()->withErrors(['Please reload the page and report if the submission failed again.']);
            return response()->json([
                'success' => false,
                'message' => 'Please reload the page and report if the submissions failed again. DO NOT MODIFY WEB PAGE THROUGH DEVELOPER TOOLS.',
            ], 400);
        }
    }

    private function isPrEnabled()
    {
        return count(PurchaseRequestMode::where('year', '=', Auth::user()->ppmp_year)->where('branches_id', '=', Auth::user()->branches_id)->where('mode', '=', 'ENABLED')->get()) === 0 ? false : true;
    }
}
