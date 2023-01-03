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
use App\Models\ItemDetailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ItemDetailController extends Controller
{
    public function all()
    {
        $allItemDetails = ItemDetail::all();

        return view('po-dashboard/item-detail-list')
            ->with('item_details', $allItemDetails);
    }

    public function delete($item_detail_id)
    {
        $getItem = ItemDetail::find($item_detail_id);
        DB::beginTransaction();
        try {
            $getItem->is_delete = 1;
            $getItem->save();
            DB::commit();
            redirect()->back()->with('success', 'Item successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Item is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            ItemDetail::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Item successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Item is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function store(Request $request, $id)
    {
        //
        $request->validate([
            'source_of_funds_id' => 'required|numeric|min:1',
            'item_purposes_id' => 'required|numeric|min:1',
            'estimated_budget' => 'required|numeric',
        ]);

        if (checkIfDeleted('item_purposes', $request->item_purposes_id) === 1) {
            return redirect()
                ->back()
                ->withErrors(["Please don't use deleted item purpose."]);
        }

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
            $newPPMP->is_delete = 0;
            $newPPMP->remarks = $remarks;
            $newPPMP->submitted_by = $submitted_by;

            $newPPMP->save();

            $lastInsertedId = $newPPMP->id;

            foreach ($ppmpFormat as $format) {
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
            die($e);
            return redirect()->back()->withErrors(["Something went wrong! Your submission isn't added to the cart."]);
        }

        return redirect('dashboard')->with('success', 'Item successfully added to PPMP cart.');
    }

    public function show($id)
    {
        //
        $user = Auth::user();

        $itemDetail = ItemDetail::leftJoin('item_categories', 'item_categories.id', '=', 'item_details.category_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->where('item_details.id', '=', $id)->select('item_details.*', 'units.uom', 'item_categories.description as cat_desc')->get();

        if (count($itemDetail) === 0) {
            abort(404);
        }
        $ppmpFormat = MilestoneFormat::find(env("MILESTONE_FORMAT"));

        $sourceOfFunds = SourceOfFund::all();
        $itemPurposes = ItemPurpose::where('is_delete', '=', 0)->get();

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

    public function get_item_detail($item_detail_id)
    {
        $itemCategories = ItemCategory::all();
        $itemDetail = ItemDetail::find($item_detail_id);
        $units = Unit::all();
        return view('global/view-item-detail')
            ->with('item_detail', $itemDetail)
            ->with('item_categories', $itemCategories)
            ->with('units', $units);
    }

    public function update_item_detail(Request $request, $item_detail_id)
    {
        DB::beginTransaction();
        try {
            $itemDetail1 = ItemDetail::find($item_detail_id);
            $itemDetail = ItemDetail::find($item_detail_id);
            $beforeChange = $itemDetail1;

            $itemDetail->description = $request->description;
            $itemDetail->article = $request->article;
            $itemDetail->price_catalogue = $request->price_catalogue;
            $itemDetail->category_id = $request->category_id;
            $itemDetail->unit_id = $request->unit_id;

            if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "PROCUREMENT_OFFICE") {
                $itemDetail->save();
            }

            $changesSummary = [];

            if ($beforeChange->description !== $itemDetail->description) {
                array_push($changesSummary, 'Description was changed from "' . $beforeChange->description . '" to "' . $itemDetail->description . '".');
            }
            if ($beforeChange->article !== $itemDetail->article) {
                array_push($changesSummary, 'Article was changed from "' . $beforeChange->article . '" to "' . $itemDetail->article . '".');
            }
            if ($beforeChange->price_catalogue !== $itemDetail->price_catalogue) {
                array_push($changesSummary, 'Price catalogue was changed from "' . $beforeChange->price_catalogue . '" to "' . $itemDetail->price_catalogue . '".');
            }
            if ($beforeChange->unit_id !== $itemDetail->unit_id) {
                array_push($changesSummary, 'Unit was changed from "' . $beforeChange->unit->uom . '" to "' . $itemDetail->unit->uom . '".');
            }
            if ($beforeChange->category_id !== $itemDetail->category_id) {
                array_push($changesSummary, 'Category was changed from "' . $beforeChange->category->description . '" to "' . $itemDetail->category->description . '".');
            }

            $recordHistory = new ItemDetailHistory();
            $recordHistory->item_details_id = $itemDetail->id;
            $recordHistory->action_by = Auth::user()->id;
            $recordHistory->before_change = json_encode($beforeChange);
            $recordHistory->after_change = json_encode($itemDetail);
            $recordHistory->changes = json_encode($changesSummary);
            $recordHistory->is_approve = Auth::user()->account_type === "admin" || Auth::user()->account_type === "PROCUREMENT_OFFICE" ? 1 : 0;
            $recordHistory->remarks = "n/a";
            $recordHistory->save();
            DB::commit();

            return redirect()->back()->with('success', Auth::user()->account_type === "admin" || Auth::user()->account_type === "PROCUREMENT_OFFICE" ? 'Item successfully updated!' : "Your changes needs to be reviewed first.");
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Item not updated. Please contact website administartor.']);
        }
    }

    public function approve_item_detail(Request $request, $item_detail_id)
    {
        DB::beginTransaction();
        try {
            $itemDetail1 = ItemDetail::find($item_detail_id);
            $itemDetail = ItemDetail::find($item_detail_id);
            $beforeChange = $itemDetail1;

            $itemDetail->description = $request->description;
            $itemDetail->article = $request->article;
            $itemDetail->price_catalogue = $request->price_catalogue;
            $itemDetail->category_id = $request->category_id;
            $itemDetail->unit_id = $request->unit_id;
            $itemDetail->is_approve = 1;
            $itemDetail->save();

            $changesSummary = [];

            array_push($changesSummary, 'Item approved.');
            if ($beforeChange->description !== $itemDetail->description) {
                array_push($changesSummary, 'Description was changed from "' . $beforeChange->description . '" to "' . $itemDetail->description . '".');
            }
            if ($beforeChange->article !== $itemDetail->article) {
                array_push($changesSummary, 'Article was changed from "' . $beforeChange->article . '" to "' . $itemDetail->article . '".');
            }
            if ($beforeChange->price_catalogue !== $itemDetail->price_catalogue) {
                array_push($changesSummary, 'Price catalogue was changed from "' . $beforeChange->price_catalogue . '" to "' . $itemDetail->price_catalogue . '".');
            }
            if ($beforeChange->unit_id !== $itemDetail->unit_id) {
                array_push($changesSummary, 'Unit was changed from "' . $beforeChange->unit->uom . '" to "' . $itemDetail->unit->uom . '".');
            }
            if ($beforeChange->category_id !== $itemDetail->category_id) {
                array_push($changesSummary, 'Category was changed from "' . $beforeChange->category->description . '" to "' . $itemDetail->category->description . '".');
            }

            $recordHistory = new ItemDetailHistory();
            $recordHistory->item_details_id = $itemDetail->id;
            $recordHistory->action_by = Auth::user()->id;
            $recordHistory->before_change = json_encode($beforeChange);
            $recordHistory->after_change = json_encode($itemDetail);
            $recordHistory->changes = json_encode($changesSummary);
            $recordHistory->save();
            DB::commit();

            return redirect()->back()->with('success', 'Item successfully approved!');
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Item not approved. Please contact website administartor.']);
        }
    }

    public function delete_item_detail($item_detail_id)
    {
        $itemToDelete = ItemDetail::find($item_detail_id);
        DB::beginTransaction();

        try {
            $itemToDelete->is_delete = 1;
            $itemToDelete->save();

            $recordHistory = new ItemDetailHistory();
            $recordHistory->item_details_id = $item_detail_id;
            $recordHistory->action_by = Auth::user()->id;
            $recordHistory->before_change = json_encode([]);
            $recordHistory->after_change = json_encode([]);
            $recordHistory->changes = json_encode(['Item deleted.']);
            $recordHistory->save();

            DB::commit();
            return redirect()->back()->with('success', 'Item successfully deleted.');
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. Item is not deleted.']);
        }
    }

    public function pending_items()
    {
        $allItemDetails = ItemDetail::all();
        return view('po-dashboard/pending-item-changes')
            ->with('item_details', $allItemDetails);
    }
}
