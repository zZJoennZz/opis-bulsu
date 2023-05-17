<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $all_settings = Setting::all();
        return view('po-dashboard/settings')->with('all_settings', $all_settings);
    }

    public function save_changes(Request $request)
    {
        $nameValidation = 'required|max:150';
        $request->validate([
            'bac_chairman' => $nameValidation,
            'university_president' => $nameValidation,
            'vice_chair_1' => $nameValidation,
            'member_1' => $nameValidation,
            'member_2' => $nameValidation,
            'member_3' => $nameValidation,
            'member_4' => $nameValidation,
            'technical_resource_person' => $nameValidation,
        ]);
        try {
            DB::beginTransaction();

            Setting::where('name', 'bac_chairman')->update(['value' => $request->bac_chairman]);
            Setting::where('name', 'university_president')->update(['value' => $request->university_president]);
            Setting::where('name', 'vice_chair_1')->update(['value' => $request->vice_chair_1]);
            Setting::where('name', 'member_1')->update(['value' => $request->member_1]);
            Setting::where('name', 'member_2')->update(['value' => $request->member_2]);
            Setting::where('name', 'member_3')->update(['value' => $request->member_3]);
            Setting::where('name', 'member_4')->update(['value' => $request->member_4]);
            Setting::where('name', 'technical_resource_person')->update(['value' => $request->technical_resource_person]);

            $mainMode = 0;
            if ($request->has('maintenance_mode')) {
                $mainMode = $request->maintenance_mode === "on" ? 1 : 0;
            }
            Setting::where('name', 'maintenance_mode')->update(['value' => $mainMode]);

            DB::commit();
            return redirect()->back()->with('success', 'Setting changes saved.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. Cannot save setting changes.']);
        }
    }

    public function budget_setting()
    {
        try {
            $settings = Setting::where('name', 'ppmp_due_month')
                ->orWhere('name', 'ppmp_due_day')
                ->get();
            return view('bo-dashboard/set-due-date')
                ->with('settings', $settings);
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['Something went wrong. Cannot access settings.']);
        }
    }

    public function budget_save_setting(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'due_month' => 'required|in:1,2,3,4,5,6,7,8,9,10,11,12',
            'due_day' => 'required|in:1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors(['Invalid values. Please try again.']);
        }

        try {
            DB::beginTransaction();
            Setting::where('name', 'ppmp_due_month')->update(['value' => $request->due_month]);
            Setting::where('name', 'ppmp_due_day')->update(['value' => $request->due_day]);
            DB::commit();

            return redirect()->back()->with('success', 'Due date successfully updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong, cannot save changes.']);
        }
    }
}
