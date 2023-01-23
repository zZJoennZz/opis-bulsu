<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function all()
    {
        return view('po-dashboard/quotation-list');
    }

    public function add()
    {
        $company_profiles = Company::all();
        $purchase_requests = PurchaseRequest::where('year', '=', Auth::user()->ppmp_year)->where('is_delete', '=', 0)->where('is_draft', '=', 0)->get();

        return view('po-dashboard/add-new-quotation')
            ->with('company_profiles', $company_profiles)
            ->with('purchase_requests', $purchase_requests);
    }
}
