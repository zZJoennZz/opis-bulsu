<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\EquipmentCode;
use App\Models\InventoryCustodian;
use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Models\ItemDetail;
use App\Models\ProProManPlan;
use App\Models\ProProManPlanHistory;
use App\Models\Notification;
use App\Models\PurchaseOrder;
use App\Models\SupplyEndUser;
use App\Models\SupplyOfficeEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //

    public function show()
    {
        $user = Auth::user();

        $viewToReturn = view('dashboard');

        if ($user->account_type === "END_USER" || $user->account_type === "admin") {
            $allCategories = ItemCategory::all();
            $allItems = ItemDetail::leftJoin('item_categories', 'item_categories.id', '=', 'item_details.category_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->where('item_details.is_approve', '=', 1)->where('item_details.is_delete', '=', 0)->select('item_details.*', 'item_categories.description as cat_desc', 'units.uom')->get();
            $is_consolidated = count(ProProManPlan::where('year', '=', Auth::user()->ppmp_year)->where('is_consolidate', '=', 1)->get()) > 0 ? true : false;
            $viewToReturn = $viewToReturn->with('categories', $allCategories)->with('items', $allItems)->with('is_consolidated', $is_consolidated);
        }

        if ($user->account_type === "BUDGET_OFFICE" || $user->account_type === "admin") {
            // $ppmpList = ProProManPlan::leftJoin('branches', 'branches.id', '=', 'pro_pro_man_plans.branches_id')->leftJoin('users', 'users.id', '=', 'pro_pro_man_plans.submitted_by')->where('year', '=', $user->ppmp_year)->select('pro_pro_man_plans.year', 'branches.id', 'branches.branch_name', DB::raw('(SELECT CONCAT(up.first_name, " ", up.last_name) as username FROM users as u LEFT JOIN user_profiles as up on u.id = up.users_id WHERE u.branches_id = pro_pro_man_plans.branches_id ORDER BY u.id DESC LIMIT 1) as username'))->groupBy('pro_pro_man_plans.branches_id')->groupBy('branches.branch_name')->groupBy('branches.id')->groupBy('pro_pro_man_plans.year')->get();
            // $ppmpLogs = array();
            // $newBudgetReq = [];
            // $approvedBudgetReq = array();
            // $previousRecords = array();

            // foreach ($ppmpList as $ppmp) {
            //     $getTotal = ProProManPlan::where('branches_id', '=', $ppmp->id)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 0)->where('is_pr_approve', '=', 0)->where('year', '=', $user->ppmp_year)->get();
            //     array_push($newBudgetReq, ["branches_id" => $ppmp->id, "count" => count($getTotal)]);

            //     $getTotal = ProProManPlan::where('branches_id', '=', $ppmp->id)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('year', '=', $user->ppmp_year)->get();
            //     array_push($approvedBudgetReq, ["branches_id" => $ppmp->id, "count" => count($getTotal)]);

            //     $getTotal = ProProManPlan::where('branches_id', '=', $ppmp->id)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1)->where('year', '=', $user->ppmp_year)->get();
            //     array_push($previousRecords, ["branches_id" => $ppmp->id, "count" => count($getTotal)]);

            //     $getTotal = ProProManPlanHistory::leftJoin('pro_pro_man_plans', 'pro_pro_man_plans.id', '=', 'pro_pro_man_plan_histories.pro_pro_man_plans_id')->where('pro_pro_man_plans.year', '=', $user->ppmp_year)->where('pro_pro_man_plans.branches_id', '=', $ppmp->id)->get();
            //     array_push($ppmpLogs, ["branches_id" => $ppmp->id, "count" => count($getTotal)]);
            // }

            // $newBudgetReq = array_filter($newBudgetReq, function ($rec) {
            //     return $rec["branches_id"] === 1;
            // })[0]["count"];
            // return response()->json($newBudgetReq);

            // $viewToReturn = $viewToReturn
            //     ->with('ppmp_list', $ppmpList)
            //     ->with('new_budget_requests', $newBudgetReq)
            //     ->with('approved_budget_request', $approvedBudgetReq)
            //     ->with('previous_records', $previousRecords)
            //     ->with('ppmp_logs', $ppmpLogs);

            $branches = Branch::with(['ppmp' => function ($builder) {
                $builder
                    ->where('year', getPpmpYear())
                    ->where('is_delete', 0)
                    ->where('is_draft', 0);
            }])
                ->where('type', '<>', 'DEVELOPER')
                ->get();
            // return $branches;
            $viewToReturn = $viewToReturn->with('branches', $branches);
        }

        if ($user->account_type === "PROCUREMENT_HEAD" || $user->account_type === "PROCUREMENT_OFFICE" || $user->account_type === "admin") {
            // $allBranches = Branch::where('type', '<>', 'DEVELOPER')->get();
            $branches = Branch::with(['ppmp' => function ($builder) {
                $builder
                    ->where('year', getPpmpYear())
                    ->where('is_delete', 0)
                    ->where('is_draft', 0);
            }])
                ->where('type', '<>', 'DEVELOPER')
                ->get();
            $ppmpRecordsCount = ProProManPlan::where('year', '=', $user->ppmp_year)->where('is_draft', '=', 0)->groupBy('branches_id')->select('branches_id')->get();

            $viewToReturn = $viewToReturn
                // ->with('all_branches', $allBranches)
                ->with('branches', $branches)
                ->with('ppmp_records_count', $ppmpRecordsCount);
        }

        if ($user->account_type === "SUPPLY_OFFICE" || $user->account_type === "admin") {
            $viewToReturn = $viewToReturn->with('hey', 'hey');
        }

        if ($user->account_type === "PROCUREMENT_HEAD" || $user->account_type === "admin") {
            $allItemDetails = ItemDetail::all();
            $viewToReturn = $viewToReturn->with('allItemDetails', $allItemDetails);
        }

        return $viewToReturn;
    }

    public function print_unsub_ppmp()
    {
        $unsub_ppmp_branches = Branch::whereDoesntHave(
            'ppmp',
            function ($query) {
                $query->where('year', getPpmpYear());
            }
        )
            ->where('type', '<>', 'DEVELOPER')
            ->get();

        return view('po-dashboard/print-unsubmitted-ppmp')->with('unsub_ppmp_branches', $unsub_ppmp_branches);
    }
}
