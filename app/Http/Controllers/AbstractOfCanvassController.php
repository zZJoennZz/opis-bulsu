<?php

namespace App\Http\Controllers;

use App\Models\AbstractOfCanvass;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbstractOfCanvassController extends Controller
{
    public function all() {
        $aocs = AbstractOfCanvass::with(['pr'])->whereHas('pr', function ($builder) {
            $builder->where('year', '=', Auth::user()->ppmp_year);
        })->get();

        return view('po-dashboard/view-abstract-of-canvasses')
            ->with('aocs', $aocs);
    }

    public function add() {
        $pr_without_abstract = PurchaseRequest::doesntHave('abstract_of_canvass')
            ->where('year', '=', Auth::user()->ppmp_year)
            ->get();
        return view('po-dashboard/add-abstract-of-canvass')
            ->with('pr_without_abstract', $pr_without_abstract);
    }
}
