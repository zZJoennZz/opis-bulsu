<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Models\ItemCategoryGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ItemCategoryController extends Controller
{
    //
    public function all()
    {
        $itemCategories = ItemCategory::all();
        $categoryGroups = ItemCategoryGroup::all();
        return view("po-dashboard/category-list")
            ->with('item_categories', $itemCategories)
            ->with('category_groups', $categoryGroups);
    }

    public function add(Request $request)
    {
        $newCategory = new ItemCategory();
        DB::beginTransaction();
        try {
            $newCategory->description = $request->description;
            $newCategory->under_of_group = $request->under_of_group;
            $newCategory->added_by = Auth::user()->id;
            $newCategory->save();
            $itemCategories = ItemCategory::all();
            $categoryGroups = ItemCategoryGroup::all();
            DB::commit();
            return redirect()->back()
                ->with('success', 'Category successfully added!')
                ->with('item_categories', $itemCategories)
                ->with('category_groups', $categoryGroups);
        } catch (Throwable $e) {
            DB::rollBack();
            $itemCategories = ItemCategory::all();
            $categoryGroups = ItemCategoryGroup::all();
            return redirect()->back()
                ->withErrors(['Something went wrong! Category not added.'])
                ->with('item_categories', $itemCategories)
                ->with('category_groups', $categoryGroups);
        }
    }

    public function get($category_id)
    {
        $category = ItemCategory::find($category_id);

        return response()->json($category, 200);
    }

    public function update(Request $request, $category_id)
    {
        $getCategory = ItemCategory::find($category_id);
        DB::beginTransaction();

        try {
            $getCategory->description = $request->description;
            $getCategory->under_of_group = $request->under_of_group;
            $getCategory->save();

            DB::commit();

            session(['success' => 'Category successfully updated!']);
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

    public function delete_single($category_id)
    {
        $getCategory = ItemCategory::find($category_id);
        DB::beginTransaction();
        try {
            $getCategory->is_delete = 1;
            $getCategory->save();
            DB::commit();
            redirect()->back()->with('success', 'Category successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Category is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            ItemCategory::whereIn('id', $request->id)->update(["is_delete" => 1]);
            // $getCategories->save();
            DB::commit();
            redirect()->back()->with('success', 'Category successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Category is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }
}
