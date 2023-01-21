<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemPurpose;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;


class ItemPurposeController extends Controller
{
    public function all()
    {
        $allItemPurposes = ItemPurpose::all();
        return view('po-dashboard/item-purpose-list')
            ->with('item_purposes', $allItemPurposes);
    }

    public function add(Request $request)
    {
        $newPurpose = new ItemPurpose();
        DB::beginTransaction();
        try {
            $newPurpose->description = $request->description;
            $newPurpose->added_by = Auth::user()->id;
            $newPurpose->save();
            $itemPurpose = ItemPurpose::all();
            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Purpose successfully added!')
                ->with('item_purpose', $itemPurpose);
        } catch (Throwable $e) {
            DB::rollBack();
            $itemPurpose = ItemPurpose::all();
            return redirect()->back()
                ->withErrors(['Something went wrong! Purpose not added.'])
                ->with('item_purpose', $itemPurpose);
        }
    }

    public function get($purpose_id)
    {
        $purpose = ItemPurpose::find($purpose_id);
        return response()->json($purpose, 200);
    }

    public function update(Request $request, $purpose_id)
    {
        $getPurpose = ItemPurpose::find($purpose_id);
        DB::beginTransaction();

        try {
            $getPurpose->description = $request->description;
            $getPurpose->save();

            DB::commit();

            session(['success' => 'Purpose successfully updated!']);
            return response()->json([
                'success' => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
            ], 400);
        }
    }

    public function delete_single($purpose_id)
    {
        $getPurpose = ItemPurpose::find($purpose_id);
        DB::beginTransaction();
        try {
            $getPurpose->is_delete = 1;
            $getPurpose->save();
            DB::commit();
            redirect()->back()->with('success', 'Item purpose successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Item purpose is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            ItemPurpose::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Item purposes successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Item purpose is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }
}
