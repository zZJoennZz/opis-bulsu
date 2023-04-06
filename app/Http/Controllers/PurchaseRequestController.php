<?php

namespace App\Http\Controllers;

use App\Models\Branch;
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
        $user = Auth::user();
        $checkIfConsolidated = ProProManPlan::where('year', '=', $user->ppmp_year)->where('is_consolidate', '=', 1)->get();
        if (count($checkIfConsolidated) >= 1) {
            $pr_records = PurchaseRequest::with(['pr_items', 'pr_items.ppmp' => function ($query) use ($user) {
                return $query->where('is_bo_approve', '=', 1)
                    ->where('is_pr_approve', '=', 1)
                    ->where('is_consolidate', '=', 1)
                    ->where('is_delete', '=', 0)
                    ->where('year', '=', $user->ppmp_year)
                    ->with('item_detail');
            }])
                ->with('branch')
                ->with('requester')
                ->where('year', '=', $user->ppmp_year)
                ->get();

            $branches = Branch::has('ppmp')
                ->with('pr_mode', function ($query) {
                    return $query->where('year', '=', Auth::user()->ppmp_year);
                })
                ->get();
        } else {
            $pr_records = [];
            $branches = [];
        }
        // return $branches;
        return view('po-dashboard/view-purchase-request')->with('pr_records', $pr_records)->with('branches', $branches);
    }

    public function pr_list()
    {
        $user = Auth::user();

        $pr_records = [];
        $is_enabled = $this->isPrEnabled();
        $return_value = view('dashboard/purchase_request_list')
            ->with('is_pr_enabled', $is_enabled);
        if ($is_enabled) {
            $pr_records = PurchaseRequest::with(['pr_items', 'pr_items.ppmp' => function ($query) use ($user) {
                return $query->where('is_bo_approve', '=', 1)
                    ->where('is_pr_approve', '=', 1)
                    ->where('is_consolidate', '=', 1)
                    ->where('is_delete', '=', 0)
                    ->where('year', '=', $user->ppmp_year)
                    ->where('branches_id', '=', $user->branches_id)
                    ->with('item_detail');
            }])
                ->with('branch')
                ->with('requester')
                ->where('branches_id', '=', $user->branches_id)
                ->where('year', '=', $user->ppmp_year)
                ->get();
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
            $available_items = ProProManPlan::where('is_draft', '=', 0)
                ->where('is_bo_approve', '=', 1)
                ->where('is_pr_approve', '=', 1)
                ->where('is_consolidate', '=', 1)
                ->where('is_delete', '=', 0)
                ->where('year', '=', Auth::user()->ppmp_year)
                ->where('branches_id', '=', Auth::user()->branches_id)
                ->whereNotIn('id', function ($query) {
                    return $query->select('pro_pro_man_plans_id')
                        ->from('purchase_request_items');
                })
                ->with('item_detail')
                ->with('milestones')
                ->with('source_of_fund')
                ->with('item_purpose')
                ->get();

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
        // return $request->all();
        $request->validate([
            'id' => 'required|min:3'
        ], [
            'id.required' => 'Please select item/s to submit.',
            'id.min' => 'Please select item/s to submit'
        ]);

        $ppmp_id = json_decode($request->id);
        $user = Auth::user();

        //2nd layer of validation
        $is_ppmp_valid_for_pr = count(ProProManPlan::whereIn('id', $ppmp_id)
            ->where('branches_id', '=', $user->branches_id)
            ->where('year', '=', $user->ppmp_year)
            ->where('is_draft', '=', 0)
            ->where('is_bo_approve', '=', 1)
            ->where('is_pr_approve', '=', 1)
            ->where('is_consolidate', '=', 1)
            ->where('is_delete', '=', 0)
            ->whereNotIn('id', function ($query) {
                return $query->select('pro_pro_man_plans_id')
                    ->from('purchase_request_items');
            })
            ->get()) === 0 ? false : true;

        DB::beginTransaction();
        if ($is_ppmp_valid_for_pr) {
            //PR NUMBER BUILDER
            // $source_of_fund = ProProManPlan::where('id', '=', $ppmp_id[0])->with(['source_of_fund'])->first();
            // $latest_pr = PurchaseRequest::with(['pr_items.ppmp'])->whereHas('pr_items.ppmp.source_of_fund', function ($builder) use ($source_of_fund) {
            //     $builder->where('id', '=', $source_of_fund->source_of_fund->id);
            // })->orderBy('id', 'desc')->where('year', '=', getPpmpYear())->first();
            // $pr_num_ctr = $latest_pr === null ? 1 : intval(substr($latest_pr->pr_number, 10, 4)) + 1;
            // $ctr_zero = "0000";
            // $pr_number = substr($source_of_fund->source_of_fund->source_of_fund, 0, 1) . '-' . getPpmpYear() . '-' . date('m', strtotime($source_of_fund->created_at)) . '-' . substr($ctr_zero, strlen($pr_num_ctr)) . $pr_num_ctr;

            $source_of_fund = ProProManPlan::where('id', $ppmp_id[0])
                ->with('source_of_fund')
                ->first();

            $latest_pr = PurchaseRequest::with(['pr_items.ppmp'])
                ->whereHas('pr_items.ppmp.source_of_fund', function ($builder) use ($source_of_fund) {
                    $builder->where('id', '=', $source_of_fund->source_of_fund->id);
                })
                ->where('year', getPpmpYear())
                ->latest()
                ->first();

            $pr_num_ctr = $latest_pr === null ? 1 : intval(substr($latest_pr->pr_number, 10, 4)) + 1;

            $pr_number = sprintf(
                '%s-%s-%s-%s%s',
                substr($source_of_fund->source_of_fund->source_of_fund, 0, 1),
                getPpmpYear(),
                date('m'),
                str_pad($pr_num_ctr, 4, '0', STR_PAD_LEFT),
                ''
            );

            $new_purchase_request = new PurchaseRequest();
            $new_purchase_request->year = $user->ppmp_year;
            $new_purchase_request->pr_number = $pr_number;
            $new_purchase_request->purpose = $request->purpose;
            $new_purchase_request->is_draft = 0;
            $new_purchase_request->is_approve = 1;
            $new_purchase_request->is_delete = 0;
            $new_purchase_request->branches_id = $user->branches_id;
            $new_purchase_request->requested_by = $user->id;
            $new_purchase_request->save();

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

    public function approve_pr($pr_id)
    {
        DB::beginTransaction();
        try {
            $pr_record = PurchaseRequest::find($pr_id);
            $pr_record->is_approve = 1;
            $pr_record->save();
            DB::commit();
            redirect()->back()->with('success', 'Purchase request approved.');
            return response()->json([
                'success' => true,
                'message' => 'Purchase request approved.'
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors(['Something went wrong. Purchase request not approved.Something went wrong. Purchase request not approved.']);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Purchase request not approved.'
            ], 400);
        }
    }

    public function toggle_pr_mode(Request $request)
    {
        $mode = $request->mode ? "ENABLED" : "DISABLED";
        $check_pr_mode = PurchaseRequestMode::where('branches_id', '=', $request->branches_id)->where('year', '=', Auth::user()->ppmp_year)->get();
        $is_pr_mode_exists = count($check_pr_mode) >= 1 ? true : false;
        // return response()->json($is_pr_mode_exists, 200);
        DB::beginTransaction();
        try {
            if ($is_pr_mode_exists) {
                $toggleMode = PurchaseRequestMode::find($check_pr_mode[0]->id);
                $toggleMode->mode = $mode;
                $toggleMode->save();
            } else {
                $newMode = new PurchaseRequestMode();
                $newMode->branches_id = $request->branches_id;
                $newMode->mode = $mode;
                $newMode->year = Auth::user()->ppmp_year;
                $newMode->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'PR toggled',
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Toggling didn\'t work.'
            ], 400);
        }
    }

    public function pr_single($pr_id)
    {
        $pr_records = PurchaseRequest::where('id', '=', $pr_id)
            ->where('year', '=', Auth::user()->ppmp_year)
            ->where('is_approve', '=', 1)
            // ->with('pr_items', function ($query) use ($company_id) {
            //     $query->whereNotIn('purchase_requests_id ', function ($query1) use ($company_id) {
            //         $query1->select('quotation_items.purchase_request_items')->from('quotation_items')->leftJoin('quotations', 'quotation_items.quotions_id', 'quotations.id')->where('quotations.companies_id', '=', $company_id);
            //     });
            // })
            ->with('branch')
            ->with(['pr_items', 'branch', 'pr_items.ppmp', 'pr_items.ppmp.source_of_fund', 'pr_items.ppmp.item_detail', 'pr_items.ppmp.item_detail.unit', 'pr_items.ppmp.item_purpose', 'pr_items.ppmp.milestones'])
            ->get();
        return response()->json($pr_records, 200);
    }

    public function pr_single_quotation($pr_id, $company_id)
    {
        $pr_records = PurchaseRequest::where('id', '=', $pr_id)
            ->where('year', '=', Auth::user()->ppmp_year)
            ->where('is_approve', '=', 1)
            ->with(['pr_items' => function ($query) use ($company_id) {
                // $query->whereNotIn('purchase_requests_id ', function ($query1) use ($company_id) {
                //     $query1->select('quotation_items.purchase_request_items')->from('quotation_items')->leftJoin('quotations', 'quotation_items.quotions_id', 'quotations.id')->where('quotations.companies_id', '=', $company_id);
                // });
                $query->whereDoesntHave('quotations.quotation', function ($query1) use ($company_id) {
                    $query1->where('year', '=', getPpmpYear())->where('companies_id', '=', $company_id);
                });
            }, 'pr_items', 'branch', 'pr_items.ppmp', 'pr_items.ppmp.source_of_fund', 'pr_items.ppmp.item_detail', 'pr_items.ppmp.item_detail.unit', 'pr_items.ppmp.item_purpose', 'pr_items.ppmp.milestones'])
            ->with('branch')
            ->get();
        return response()->json($pr_records, 200);
    }

    public function pr_single_user($pr_id)
    {
        $pr_records = PurchaseRequest::where('id', '=', $pr_id)
            ->where('year', '=', Auth::user()->ppmp_year)
            ->with(['requester', 'pr_items', 'branch', 'pr_items.ppmp', 'pr_items.ppmp.source_of_fund', 'pr_items.ppmp.item_detail', 'pr_items.ppmp.item_detail.unit', 'pr_items.ppmp.item_purpose', 'pr_items.ppmp.milestones'])
            ->get();

        if ($pr_records[0]->requester->id === Auth::user()->id) {
            return response()->json($pr_records, 200);
        } else {
            return response()->json('You are not allowed to access this.', 401);
        }
    }

    private function isPrEnabled()
    {
        return count(PurchaseRequestMode::where('year', '=', Auth::user()->ppmp_year)->where('branches_id', '=', Auth::user()->branches_id)->where('mode', '=', 'ENABLED')->get()) === 0 ? false : true;
    }
}
