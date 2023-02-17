<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class BacResoController extends Controller
{
    //
    public function add_new()
    {
        $company_list = Company::all();
        return view('po-dashboard/add-bac-reso')
            ->with('company_list', $company_list);
    }
}
