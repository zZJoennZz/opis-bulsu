<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $all_settings = Setting::all();
        return view('po-dashboard/settings')->with('all_settings', $all_settings);
    }
}
