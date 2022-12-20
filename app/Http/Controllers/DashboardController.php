<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Models\ItemDetail;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //

    public function show()
    {
        $allCategories = ItemCategory::all();
        $allItems = ItemDetail::leftJoin('item_categories', 'item_categories.id', '=', 'item_details.category_id')->leftJoin('units', 'units.id', '=', 'item_details.unit_id')->select('item_details.*', 'item_categories.description as cat_desc', 'units.uom')->get();

        $user = Auth::user();

        // session(['j_user_type' => $user->account_type]);

        return view('dashboard')->with('categories', $allCategories)->with('items', $allItems);
    }
}
