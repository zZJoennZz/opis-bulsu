<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemMaintenanceController extends Controller
{
    public function select_form()
    {
        return view('so-dashboard.select-maintenance-form');
    }

    public function maintenance_form()
    {
        return "Hey";
    }
}
