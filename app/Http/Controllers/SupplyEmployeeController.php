<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyPosition;
use App\Models\SupplyOfficeEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupplyEmployeeController extends Controller
{
    //
    public function all()
    {
        $supply_employees = SupplyOfficeEmployee::with(['position'])->get();
        $positions = SupplyPosition::where('type', '=', 'SUPPLY_OFFICE_EMPLOYEE')->get();
        return view('so-dashboard/manage-supply-employees')
            ->with('positions', $positions)
            ->with('supply_employees', $supply_employees);
    }

    public function post_add(Request $request)
    {

        try {
            $nameVal = 'required|regex:/^[\pL\s\-]+$/u';
            $request->validate([
                'first_name' => $nameVal,
                'middle_name' => $nameVal,
                'last_name' => $nameVal,
                'position' => 'required|exists:supply_positions,id'
            ]);

            DB::beginTransaction();
            $new_supply_employee = new SupplyOfficeEmployee();
            $new_supply_employee->first_name = $request->first_name;
            $new_supply_employee->middle_name = $request->middle_name;
            $new_supply_employee->last_name = $request->last_name;
            $new_supply_employee->supply_positions_id = $request->position;
            $new_supply_employee->added_by = Auth::user()->id;
            $new_supply_employee->save();
            DB::commit();

            return redirect()->back()->with('success', 'Supply employee saved.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['Something went wrong! Supply employee is not saved. Please contact website administrator.']);
        }
    }
}
