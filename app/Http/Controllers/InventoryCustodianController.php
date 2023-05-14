<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InspectionAndAcceptance;
use App\Models\SourceOfFund;
use App\Models\SupplyOfficeEmployee;
use App\Models\SupplyEndUser;

class InventoryCustodianController extends Controller
{
    //
    public function add()
    {
        $iars = InspectionAndAcceptance::with(['purchase_order'])->get();
    }
}
