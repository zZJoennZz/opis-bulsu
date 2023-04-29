<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ItemCategoryGroupSection;
use Illuminate\Http\Request;
use App\Models\ItemDetail;
use App\Models\MilestoneFormat;
use App\Models\ProProManPlan;
use App\Models\MilestoneOfActivity;
use App\Models\SourceOfFund;
use App\Models\ItemPurpose;
use App\Models\ProProManPlanHistory;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class PPMPController extends Controller
{
    //
    private $select_all_ppmp = 'pro_pro_man_plans.*';
    public function get()
    {
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
        $ppmpFormat = json_decode($ppmpFormat->format);
        $user = Auth::user();
        $ppmpDrafts = ProProManPlan::leftJoin('item_details', 'item_details.id', '=', 'pro_pro_man_plans.item_details_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->select($this->select_all_ppmp, 'item_details.description', 'units.uom', 'item_details.price_catalogue')->where('year', '=', $user->ppmp_year)->where('submitted_by', '=', $user->id)->where('is_draft', '=', '0')->where('pro_pro_man_plans.branches_id', '=', $user->branches_id)->with(['pr_item'])->get();

        $ppmpIds = [];
        foreach ($ppmpDrafts as $draft) {
            array_push($ppmpIds, $draft->id);
        }

        $milestoneOfActivities = MilestoneOfActivity::whereIn('pro_pro_man_plans_id', $ppmpIds)->get();

        return view('dashboard/ppmp')
            ->with('ppmp_format', $ppmpFormat)
            ->with('cart_items', $ppmpDrafts)
            ->with('milestones', $milestoneOfActivities);
    }

    public function new_ppmp_request($branch_id)
    {
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
        $ppmpFormat = json_decode($ppmpFormat->format);

        $user = Auth::user();

        $getSourceOfFund = ProProManPlan::where('year', $user->ppmp_year)->where('branches_id', $branch_id)->first()->source_of_funds_id;
        $getSourceOfFund = $getSourceOfFund === null || $getSourceOfFund === "" || $getSourceOfFund === 0 ? 1 : $getSourceOfFund;

        $ppmpNewRequests = ProProManPlan::leftJoin('item_details', 'item_details.id', '=', 'pro_pro_man_plans.item_details_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->select($this->select_all_ppmp, 'item_details.description', 'units.uom', 'item_details.price_catalogue', 'pro_pro_man_plans.submitted_by')->where('year', '=', $user->ppmp_year)->where('is_draft', '=', '0')->where('pro_pro_man_plans.branches_id', '=', $branch_id)->where('pro_pro_man_plans.is_bo_approve', '=', 0)->get();

        if (count($ppmpNewRequests) <= 0) {
            return redirect()->route('bo-dashboard.show');
        }

        $ppmpIds = [];
        foreach ($ppmpNewRequests as $newRequest) {
            array_push($ppmpIds, $newRequest->id);
        }

        $milestoneOfActivities = MilestoneOfActivity::whereIn('pro_pro_man_plans_id', $ppmpIds)->get();
        $sourceOfFunds = SourceOfFund::all();
        return view('bo-dashboard/new-ppmp-budget-request')
            ->with('ppmp_format', $ppmpFormat)
            ->with('ppmp_items', $ppmpNewRequests)
            ->with('milestones', $milestoneOfActivities)
            ->with('source_of_funds', $sourceOfFunds)
            ->with('branch_id', $branch_id)
            ->with('currentSourceOfFund', $getSourceOfFund);
    }

    public function ppmp_approval($branch_id)
    {
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
        $ppmpFormat = json_decode($ppmpFormat->format);

        $user = Auth::user();

        $ppmpNewRequests = ProProManPlan::leftJoin('item_details', 'item_details.id', '=', 'pro_pro_man_plans.item_details_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->leftJoin('branches', 'branches.id', '=', 'pro_pro_man_plans.branches_id')->select($this->select_all_ppmp, 'item_details.description', 'units.uom', 'item_details.price_catalogue', 'pro_pro_man_plans.submitted_by', 'branches.branch_name')->where('year', '=', $user->ppmp_year)->where('is_draft', '=', '0')->where('pro_pro_man_plans.branches_id', '=', $branch_id)->where('pro_pro_man_plans.is_bo_approve', '=', 1)->where('pro_pro_man_plans.is_pr_approve', '=', 0)->get();

        if (count($ppmpNewRequests) <= 0) {
            return redirect()->route('bo-dashboard.show');
        }

        $ppmpIds = [];
        foreach ($ppmpNewRequests as $newRequest) {
            array_push($ppmpIds, $newRequest->id);
        }

        $milestoneOfActivities = MilestoneOfActivity::whereIn('pro_pro_man_plans_id', $ppmpIds)->get();

        return view('po-dashboard/bo-approved-ppmp')
            ->with('ppmp_format', $ppmpFormat)
            ->with('ppmp_items', $ppmpNewRequests)
            ->with('milestones', $milestoneOfActivities)
            ->with('branch_id', $branch_id);
    }

    public function po_send_back(Request $request, $user_id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $newNotif = new Notification();
            $newNotif->title = "Procurement office sent back the PPMP.";
            $newNotif->message = "The Procurement office has sent back your PPMP request. Click here to see changes.";
            $newNotif->url = "/ppmp-cart";
            $newNotif->is_read = false;
            $newNotif->sent_to = $user_id;
            $newNotif->sent_by = $user->id;
            $newNotif->save();
            ProProManPlan::whereIn('id', $request->all())->update(['is_draft' => 1, 'is_bo_approve' => 0]);
            DB::commit();
            return response()->json([
                "success" => true,
                "message" => "Changes was sent back to the user.",
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Something went wrong. Notification not sent to user."
            ], 400);
        }
    }

    public function po_approve_ppmp(Request $request)
    {
        DB::beginTransaction();
        try {
            ProProManPlan::whereIn('id', $request->all())->update(['is_pr_approve' => 1]);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e,
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'PPMP successfully approved.'
        ], 200);
    }

    public function po_approved_ppmp($branch_id)
    {
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
        $ppmpFormat = json_decode($ppmpFormat->format);

        $user = Auth::user();

        $ppmpNewRequests = ProProManPlan::leftJoin('item_details', 'item_details.id', '=', 'pro_pro_man_plans.item_details_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->select($this->select_all_ppmp, 'item_details.description', 'units.uom', 'item_details.price_catalogue')->where('year', '=', $user->ppmp_year)->where('is_draft', '=', '0')->where('pro_pro_man_plans.branches_id', '=', $branch_id)->where('pro_pro_man_plans.is_bo_approve', '=', 1)->get();

        $ppmpIds = [];
        foreach ($ppmpNewRequests as $newRequest) {
            array_push($ppmpIds, $newRequest->id);
        }

        $milestoneOfActivities = MilestoneOfActivity::whereIn('pro_pro_man_plans_id', $ppmpIds)->get();

        $endUser = Branch::find($branch_id)->branch_name;
        return view('po-dashboard/po-approved-ppmp')
            ->with('ppmp_format', $ppmpFormat)
            ->with('ppmp_items', $ppmpNewRequests)
            ->with('milestones', $milestoneOfActivities)
            ->with('brach_name', $endUser);
    }

    public function approved_ppmp_request($branch_id)
    {
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
        $ppmpFormat = json_decode($ppmpFormat->format);

        $user = Auth::user();

        $ppmpNewRequests = ProProManPlan::leftJoin('item_details', 'item_details.id', '=', 'pro_pro_man_plans.item_details_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->select($this->select_all_ppmp, 'item_details.description', 'units.uom', 'item_details.price_catalogue')->where('year', '=', $user->ppmp_year)->where('is_draft', '=', '0')->where('pro_pro_man_plans.branches_id', '=', $branch_id)->where('pro_pro_man_plans.is_bo_approve', '=', 1)->get();

        $ppmpIds = [];
        foreach ($ppmpNewRequests as $newRequest) {
            array_push($ppmpIds, $newRequest->id);
        }

        $milestoneOfActivities = MilestoneOfActivity::whereIn('pro_pro_man_plans_id', $ppmpIds)->get();

        return view('bo-dashboard/approved-ppmp-budget-request')
            ->with('ppmp_format', $ppmpFormat)
            ->with('ppmp_items', $ppmpNewRequests)
            ->with('milestones', $milestoneOfActivities)
            ->with('branch_id', $branch_id);
    }

    public function approve_ppmp_request(Request $request)
    {
        if ($request->source_of_funds_id === "1") {
            return redirect()->back()->withErrors(['Select source of funds first.']);
        }
        DB::beginTransaction();
        try {
            ProProManPlan::where('year', '=', getPpmpYear())
                ->where('is_draft', '=', 0)
                ->where('is_bo_approve', '=', 0)
                ->where('is_pr_approve', '=', 0)
                ->where('branches_id', '=', $request->branch)
                ->update(['is_bo_approve' => 1, 'source_of_funds_id' => $request->source_of_funds_id]);

            //to record history log
            $getAll = ProProManPlan::where('year', '=', getPpmpYear())
                ->where('is_draft', '=', 0)
                ->where('is_bo_approve', '=', 1)
                ->where('is_pr_approve', '=', 0)
                ->where('branches_id', '=', $request->branch)
                ->get();
            foreach ($getAll as $ppmp) {
                $ppmpNewHistory = new ProProManPlanHistory();
                $ppmpNewHistory->pro_pro_man_plans_id = $ppmp->id;
                $ppmpNewHistory->before_state = json_encode(['state' => 'SAME']);
                $ppmpNewHistory->after_state = json_encode(['state' => 'SAME']);
                $ppmpNewHistory->remarks = 'Approved';
                $ppmpNewHistory->is_confirm = 1;
                $ppmpNewHistory->changes_summary = json_encode(['Approved']);
                $ppmpNewHistory->record_by = Auth::user()->id;

                $ppmpNewHistory->save();
            }

            DB::commit();
            session('success', 'Budget approved.');
            return redirect()->route('dashboard.show');
        } catch (Throwable $e) {
            DB::rollBack();
            // return response()->json([
            //     'success' => false,
            //     'message' => "Something went wrong. Please contact website administrator.",
            // ], 400);

            return redirect()->back()->withErrors(['Something went wrong. Please contact website administrator.']);
        }
        // return response()->json([
        //     'success' => true,
        //     'message' => 'PPMP successfully approved.'
        // ], 200);
    }

    public function get_ppmp_record($ppmp_id)
    {
        $user = Auth::user();
        // if ($user->account_type === "BUDGET_OFFICE" || $user->account_type === "admin") {
        //     $ppmpRecord = ProProManPlan::leftJoin('branches', 'branches.id', '=', 'pro_pro_man_plans.branches_id')->where('year', '=', $user->ppmp_year)->where('pro_pro_man_plans.is_bo_approve', '<>', 1)->where('pro_pro_man_plans.is_pr_approve', '<>', 1)->select($this->select_all_ppmp, 'branches.branch_name')->find($ppmp_id);
        // } else {
        //     $ppmpRecord = ProProManPlan::leftJoin('branches', 'branches.id', '=', 'pro_pro_man_plans.branches_id')->where('year', '=', $user->ppmp_year)->where('pro_pro_man_plans.is_pr_approve', '<>', 1)->select($this->select_all_ppmp, 'branches.branch_name')->find($ppmp_id);
        // }
        $ppmpRecord = ProProManPlan::leftJoin('branches', 'branches.id', '=', 'pro_pro_man_plans.branches_id')->where('year', '=', $user->ppmp_year)->select($this->select_all_ppmp, 'branches.branch_name')->doesntHave('pr_item')->find($ppmp_id);

        if (!empty($ppmpRecord)) {
            if ($ppmpRecord->submitted_by !== $user->id && $user->account_type !== "admin" && $user->account_type !== "PROCUREMENT_OFFICE" && $user->account_type !== "BUDGET_OFFICE") {
                return redirect()->back()->withErrors(['You are not allowed to edit this.']);
            }

            $itemDetail = ItemDetail::leftJoin('item_categories', 'item_categories.id', '=', 'item_details.category_id')
                ->leftJoin('units', 'units.id', '=', 'item_details.unit_id')
                ->where('item_details.id', '=', $ppmpRecord->item_details_id)
                ->select('item_details.*', 'units.uom', 'item_categories.description as cat_desc')
                ->get();

            $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
            $sourceOfFunds = SourceOfFund::all();
            $itemPurposes = ItemPurpose::where('is_delete', '=', 0)->get();

            return view('bo-dashboard/edit-ppmp-request')
                ->with('item_detail', $itemDetail)
                ->with('ppmp_year', $user->ppmp_year)
                ->with('ppmp_format', $ppmpFormat->format)
                ->with('source_of_funds', $sourceOfFunds)
                ->with('item_purposes', $itemPurposes)
                ->with('ppmp_record', $ppmpRecord)
                ->with('milestone_values', $ppmpRecord->milestones);
        } else {
            return redirect()->route('dashboard.show')->withErrors(['Already have PR or the record does not exist.']);
        }
    }

    public function update_ppmp(Request $request, $ppmp_id)
    {
        $request->validate([
            'item_purposes_id' => 'required|numeric|min:1',
            'estimated_budget' => 'required|numeric',
        ]);
        $user = Auth::user();
        $item_purposes_id = $request->item_purposes_id;
        $estimated_budget = $request->estimated_budget;
        $is_priority = $request->is_priority === "yes" ? 1 : 0;
        $remarks = $request->remarks;
        $ppmpRecord = ProProManPlan::where('year', '=', $user->ppmp_year)->doesntHave('pr_item')->find($ppmp_id);
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
        $newMilestones = [];

        if ($ppmpRecord->submitted_by !== $user->id && $user->account_type !== "admin" && $user->account_type !== "PROCUREMENT_OFFICE" && $user->account_type !== "BUDGET_OFFICE") {
            return redirect()->back()->withErrors(['You are not allowed to edit this.']);
        }

        foreach (json_decode($ppmpFormat->format) as $field) {
            array_push(
                $newMilestones,
                [
                    'milestone_formats_id' => env("MILESTONE_FORMAT"),
                    'milestone_value_id' => $field->id,
                    'milestone_value' => $request[$field->id],
                    'pro_pro_man_plans_id' => $ppmp_id,
                ]
            );
        }

        $oldState = [
            'source_of_funds_id' => $ppmpRecord->source_of_funds_id,
            'item_purposes_id' => $ppmpRecord->item_purposes_id,
            'estimated_budget' => $ppmpRecord->estimated_budget,
            'is_priority' => $ppmpRecord->is_priority,
            'remarks' => $ppmpRecord->remarks,
            'milestones' => $ppmpRecord->milestones,
        ];

        $newState = [
            'source_of_funds_id' => $request->source_of_funds_id,
            'item_purposes_id' => $request->item_purposes_id,
            'estimated_budget' => $request->estimated_budget,
            'is_priority' => $request->is_priority === "yes" ? 1 : 0,
            'remarks' => $request->remarks,
            'milestones' => $newMilestones,
        ];

        $summaryLog = [];
        if (intval($oldState["item_purposes_id"]) !== intval($newState["item_purposes_id"])) {
            array_push($summaryLog, "Item purpose was changed from " . ItemPurpose::find($oldState["item_purposes_id"])->description . " to " . ItemPurpose::find($newState["item_purposes_id"])->description . ".");
        }
        if (floatval($oldState["estimated_budget"]) !== floatval($newState["estimated_budget"])) {
            array_push($summaryLog, "Estimated budget was adjusted from " . $oldState["estimated_budget"] . " to " . $newState["estimated_budget"] . ".");
        }
        if (intval($oldState["is_priority"]) !== intval($newState["is_priority"])) {
            $fromMsg = "";
            $toMsg = "";
            if (intval($oldState["is_priority"])) {
                $fromMsg = "'Priority'";
            } else {
                $fromMsg = "'Not Priority'";
            }

            if (intval($newState["is_priority"])) {
                $toMsg = "'Priority'";
            } else {
                $toMsg = "'Not Priority'";
            }
            array_push(
                $summaryLog,
                "Priority was changed from " .  $fromMsg . " to " . $toMsg . "."
            );
        }
        if (trim($oldState["remarks"]) !== trim($newState["remarks"])) {
            array_push($summaryLog, "Remarks was changed from '" . $oldState["remarks"] . "' to '" . $newState["remarks"] . "'.");
        }

        for ($i = 0; $i < count(json_decode($ppmpFormat->format)); $i++) {
            // var_dump($oldState["milestones"][$i]->milestone_value);
            // echo " - ";
            // var_dump($newState["milestones"][$i]["milestone_value"]);
            // echo "<br />";
            if (intval($oldState["milestones"][$i]->milestone_value) !== intval($newState["milestones"][$i]["milestone_value"])) {
                array_push($summaryLog, "Milestone quantity of " . ucfirst($oldState["milestones"][$i]->milestone_value_id) . " from " . $oldState["milestones"][$i]->milestone_value . " to " . $newState["milestones"][$i]["milestone_value"] . ".");
            }
        }

        if (count($summaryLog) === 0) {
            return redirect()->route('get-ppmp-record.show', ['ppmp_id' => $ppmp_id])->with('success', 'No changes made.');
        }

        DB::beginTransaction();
        try {
            $ppmpRecord->item_purposes_id = $item_purposes_id;
            $ppmpRecord->estimated_budget = $estimated_budget;
            $ppmpRecord->is_priority = $is_priority;
            $ppmpRecord->remarks = $remarks;

            $ppmpRecord->is_bo_approve = 0;
            $ppmpRecord->is_pr_approve = 0;
            $ppmpRecord->is_consolidate = 0;

            $ppmpRecord->save();

            foreach (json_decode($ppmpFormat->format) as $field) {
                $milestoneFind = MilestoneOfActivity::where('pro_pro_man_plans_id', '=', $ppmp_id)->where('milestone_value_id', '=', $field->id)->first();
                $milestoneFind->milestone_value = $request[$field->id];
                $milestoneFind->save();
            }

            $ppmpNewHistory = new ProProManPlanHistory();
            $ppmpNewHistory->pro_pro_man_plans_id = $ppmp_id;
            $ppmpNewHistory->before_state = json_encode($oldState);
            $ppmpNewHistory->after_state = json_encode($newState);
            $ppmpNewHistory->remarks = $request->remarks;
            $ppmpNewHistory->is_confirm = 0;
            $ppmpNewHistory->changes_summary = json_encode($summaryLog);
            $ppmpNewHistory->record_by = $user->id;

            $ppmpNewHistory->save();

            //send notifications to budget office users
            if ($user->account_type === "END_USER") {
                $bousers = User::where('account_type', '=', "BUDGET_OFFICE")->orWhere('account_type', '=', 'admin')->get();

                foreach ($bousers as $bo) {
                    sendNotification($bo->id, "New Revision", "PPMP record has been revised and requires you to review. Check here!", "/new-ppmp-request/" . $user->branches_id);
                }
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(["message" => "Something went wrong! Your update isn't submitted."]);
        }

        return redirect()->route('get-ppmp-record.show', ['ppmp_id' => $ppmp_id])->with('success', 'PPMP record successfully updated!');
    }

    public function send_back(Request $request, $user_id)
    {
        // $user = Auth::user();
        DB::beginTransaction();
        try {
            // $newNotif = new Notification();
            // $newNotif->title = "Budget office sent back the PPMP.";
            // $newNotif->message = "The budget office has sent back your PPMP request with changes. Click here to check!";
            // $newNotif->url = "/ppmp-cart";
            // $newNotif->is_read = false;
            // $newNotif->sent_to = $user_id;
            // $newNotif->sent_by = $user->id;
            // $newNotif->save();
            ProProManPlan::whereIn('id', $request->all())->update(['is_draft' => 1]);
            DB::commit();
            sendNotification($user_id, 'Budget office sent back the PPMP.', 'The budget office has sent back your PPMP request with changes. Click here to check!', '/ppmp-cart');
            return response()->json([
                "success" => true,
                "message" => "Changes was sent back to the user.",
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                "success" => false,
                "message" => "Something went wrong. Notification not sent to user."
            ], 400);
        }
    }

    public function ppmp_activity_log($branch_id)
    {
        $ppmpHistories = ProProManPlanHistory::leftJoin('pro_pro_man_plans', 'pro_pro_man_plans.id', '=', 'pro_pro_man_plan_histories.pro_pro_man_plans_id')->leftJoin('item_details', 'item_details.id', '=', 'pro_pro_man_plans.item_details_id')->leftJoin('branches', 'branches.id', '=', 'pro_pro_man_plans.branches_id')->leftJoin('users', 'users.id', '=', 'pro_pro_man_plans.submitted_by')->select('pro_pro_man_plan_histories.*', 'pro_pro_man_plans.branches_id', 'item_details.description as product_name', 'branches.branch_name', DB::raw('(SELECT CONCAT(up.first_name, " ", up.last_name) as username FROM users as u LEFT JOIN user_profiles as up on u.id = up.users_id WHERE u.branches_id = pro_pro_man_plans.branches_id ORDER BY u.id DESC LIMIT 1) as username'))->where('pro_pro_man_plans.branches_id', '=', $branch_id)->where('pro_pro_man_plans.year', '=', Auth::user()->ppmp_year)->orderBy('pro_pro_man_plan_histories.created_at', 'DESC')->get();

        return view('bo-dashboard/ppmp-activity-log')->with('ppmp_histories', $ppmpHistories)->with('branch_id', $branch_id);
    }

    public function previous_ppmp($branch_id)
    {
        $ppmpBefore = ProProManPlan::where('year', '<>', Auth::user()->ppmp_year)->where('branches_id', '=', $branch_id)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1)->select('year', 'branches_id')->groupBy('year')->groupBy('branches_id')->get();
        $branch = Branch::find($branch_id);
        return view('po-dashboard/previous-ppmp')
            ->with('ppmp_before', $ppmpBefore)
            ->with('branch', $branch);
    }

    public function previous_ppmp_open($branch_id, $year)
    {
        $ppmpRecord = ProProManPlan::where('branches_id', '=', $branch_id)->where('year', '=', $year)->where('is_delete', '=', 0)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1)->get();
        $branch = Branch::find($branch_id);
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));

        $ppmp_report = ItemCategoryGroupSection::with(['category_groups', 'category_groups.categories', 'category_groups.categories.item_details', 'category_groups.categories.item_details.unit'])->with('category_groups.categories.item_details.ppmp', function ($query) use ($year, $branch_id) {
            return $query->where('year', '=', $year)->where('branches_id', '=', $branch_id)->with('milestones');
        })->get();

        return view('po-dashboard/view-previous-ppmp')
            ->with('branch', $branch)
            ->with('record_year', $year)
            ->with('ppmp_record', $ppmpRecord)
            ->with('ppmp_format', $ppmpFormat)
            ->with('ppmp_report', $ppmp_report);
    }
}
