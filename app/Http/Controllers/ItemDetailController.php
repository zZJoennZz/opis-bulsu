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

    public function submit_item_detail()
    {
    }
}
