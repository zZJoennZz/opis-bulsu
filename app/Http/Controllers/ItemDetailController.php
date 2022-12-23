<?php

namespace App\Http\Controllers;

use App\Models\ItemDetail;
use App\Models\ItemCategory;
use App\Models\MilestoneFormat;
use App\Models\SourceOfFund;
use App\Models\ItemPurpose;
use App\Models\ProProManPlan;
use App\Models\MilestoneOfActivity;
use App\Models\Unit;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ItemDetailController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        //
        $validation = $request->validate([
            'source_of_funds_id' => 'required|numeric|min:1',
            'item_purposes_id' => 'required|numeric|min:1',
            'estimated_budget' => 'required|numeric',
        ]);


        $user = Auth::user();

        $item_details_id = $id;
        $year = $user->ppmp_year;
        $branches_id = $user->branches_id;
        $is_draft = true;
        $is_bo_approve = false;
        $is_pr_approve = false;
        $source_of_funds_id = $request->source_of_funds_id;
        $item_purposes_id = $request->item_purposes_id;
        $estimated_budget = $request->estimated_budget;
        $is_priority = $request->is_priority === "yes" ? 1 : 0;
        $remarks = $request->remarks;
        $submitted_by = $user->id;

        $ppmpFormat = json_decode(MilestoneFormat::find(env("MILESTONE_FORMAT"))->format);

        DB::beginTransaction();
        try {
            $newPPMP = new ProProManPlan();
            $newPPMP->item_details_id = $item_details_id;
            $newPPMP->year = $year;
            $newPPMP->branches_id = $branches_id;
            $newPPMP->is_draft = $is_draft;
            $newPPMP->is_bo_approve = $is_bo_approve;
            $newPPMP->is_pr_approve = $is_pr_approve;
            $newPPMP->source_of_funds_id = $source_of_funds_id;
            $newPPMP->item_purposes_id = $item_purposes_id;
            $newPPMP->estimated_budget = $estimated_budget;
            $newPPMP->is_priority = $is_priority;
            $newPPMP->remarks = $remarks;
            $newPPMP->submitted_by = $submitted_by;

            $newPPMP->save();

            $lastInsertedId = $newPPMP->id;

            foreach ($ppmpFormat as $format) {
                // echo $request[$format->id];
                $newMilestone = new MilestoneOfActivity();
                $newMilestone->pro_pro_man_plans_id = $lastInsertedId;
                $newMilestone->milestone_formats_id = env("MILESTONE_FORMAT");
                $newMilestone->milestone_value_id = $format->id;
                $newMilestone->milestone_value = $request[$format->id];

                $newMilestone->save();
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(["message" => "Something went wrong! Your submission isn't added to the cart."]);
        }

        return redirect('dashboard')->with('success', 'Item successfully added to PPMP cart.');
    }

    public function show($id)
    {
        //
        $user = Auth::user();

        $itemDetail = ItemDetail::leftJoin('item_categories', 'item_categories.id', '=', 'item_details.category_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->where('item_details.id', '=', $id)->select('item_details.*', 'units.uom', 'item_categories.description as cat_desc')->get();

        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));
        $sourceOfFunds = SourceOfFund::all();
        $itemPurposes = ItemPurpose::all();

        return view('dashboard/item_detail')
            ->with('item_detail', $itemDetail)
            ->with('ppmp_year', $user->ppmp_year)
            ->with('ppmp_format', $ppmpFormat->format)
            ->with('source_of_funds', $sourceOfFunds)
            ->with('item_purposes', $itemPurposes);
    }

    public function new_item_detail()
    {
        $itemCategories = ItemCategory::all();
        $units = Unit::all();
        return view('global/add-new-item-detail')
            ->with('item_categories', $itemCategories)
            ->with('units', $units);
    }

    public function submit_item_detail(Request $request)
    {
        $request->validate([
            'category_id' => 'min:1',
            'unit_id' => 'min:1',
            'description' => 'required|min:3',
            'article' => 'required|min:3',
            'price_catalogue' => 'required|numeric|min:1'
        ]);

        DB::beginTransaction();
        $user = Auth::user();
        $success_message = $user->account_type === "PROCUREMENT_OFFICE" || $user->account_type === "admin" ? "Item detail successfully saved." : "Item detail successfully submitted for review!";

        try {
            $newItem = new ItemDetail();
            $newItem->description = $request->description;
            $newItem->article = $request->article;
            $newItem->price_catalogue = $request->price_catalogue;
            $newItem->category_id = $request->category_id;
            $newItem->unit_id = $request->unit_id;
            $newItem->is_approve = $user->account_type === "PROCUREMENT_OFFICE" || $user->account_type === "admin" ? 1 : 0;
            $newItem->is_delete = 0;
            $newItem->added_by = $user->id;
            $newItem->save();

            if ($user->account_type !== "PROCUREMENT_OFFICE" && $user->account_type !== "admin") {
                $users = User::where('account_type', '=', 'PROCUREMENT_OFFICE')->orWhere('account_type', '=', 'admin')->get();

                foreach ($users as $user) {
                    $newNotif = new Notification();
                    $newNotif->title = "New item detail";
                    $newNotif->message = "A new item detail has been added and required your approval. Please review here!";
                    $newNotif->url = "/view-item-detail/" . $newItem->id;
                    $newNotif->is_read = false;
                    $newNotif->sent_to = $user->id;
                    $newNotif->sent_by = $user->id;
                    $newNotif->save();
                }
            }

            DB::commit();
            return redirect()->back()->with('success', $success_message);
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong! Your submission failed.']);
        }
    }
}
