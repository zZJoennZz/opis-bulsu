<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemDetail;
use App\Models\Notification;
use App\Models\MilestoneFormat;
use App\Models\ProProManPlan;
use App\Models\MilestoneOfActivity;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class CartController extends Controller
{
    // fetch the cart for the configured year
    public function get()
    {
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
        $ppmpFormat = json_decode($ppmpFormat->format);
        $user = Auth::user();
        $ppmpDrafts = ProProManPlan::leftJoin('item_details', 'item_details.id', '=', 'pro_pro_man_plans.item_details_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->select('pro_pro_man_plans.*', 'item_details.description', 'units.uom', 'item_details.price_catalogue')->where('pro_pro_man_plans.year', '=', $user->ppmp_year)->where('pro_pro_man_plans.submitted_by', '=', $user->id)->where('pro_pro_man_plans.is_draft', '=', '1')->where('pro_pro_man_plans.is_delete', '=', "0")->get();

        $ppmpIds = [];
        foreach ($ppmpDrafts as $draft) {
            array_push($ppmpIds, $draft->id);
        }

        $milestoneOfActivities = MilestoneOfActivity::whereIn('pro_pro_man_plans_id', $ppmpIds)->get();

        return view('dashboard/ppmp_cart')
            ->with('ppmp_format', $ppmpFormat)
            ->with('cart_items', $ppmpDrafts)
            ->with('milestones', $milestoneOfActivities);
    }

    public function submit(Request $request)
    {
        DB::beginTransaction();
        try {
            ProProManPlan::whereIn('id', $request->all())->update(['is_draft' => 0]);
            $ppmpBranch = ProProManPlan::whereIn('id', $request->all())->get();
            $users = User::where('account_type', '=', 'BUDGET_OFFICE')->get();

            foreach ($users as $user) {
                $newNotif = new Notification();
                $newNotif->title = "New PPMP record has been submitted.";
                $newNotif->message = "An end user sent a new PPMP record for review. Please check here!";
                $newNotif->url = "/new-ppmp-request/" . $ppmpBranch[0]->branches_id;
                $newNotif->is_read = false;
                $newNotif->sent_to = $user->id;
                $newNotif->sent_by = Auth::user()->id;
                $newNotif->save();
            }
            // DB::rollBack();
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
            'message' => 'PPMP cart successfully submitted.'
        ], 200);
    }
}
