<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyPosition;
use App\Models\SupplyOfficeEmployee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
    public function get($enduser_id)
    {
        $enduser = SupplyOfficeEmployee::find($enduser_id);

        return response()->json($enduser, 200);
    }

    public function update(Request $request, $enduser_id)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            'middle_name' => ['required'],
            'last_name' => ['required'],
            'position' => ['required']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors);
        }


        $UpdateSupplyEmployee = SupplyOfficeEmployee::find($enduser_id);
        DB::beginTransaction();
        try {

        $UpdateSupplyEmployee->first_name = $request->first_name;
        $UpdateSupplyEmployee->middle_name = $request->middle_name;
        $UpdateSupplyEmployee->last_name = $request->last_name; 
        $UpdateSupplyEmployee->supply_positions_id = $request->position;        
        $UpdateSupplyEmployee->save();

        DB::commit();

        session(['success' => 'Supply Enduser successfully updated!']);
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

    public function delete_single($branch_id)
    {
        $getSupplyOfficeEmployee = SupplyOfficeEmployee::find($branch_id);
        DB::beginTransaction();
        try {
            $getSupplyOfficeEmployee->is_delete = 1;
            $getSupplyOfficeEmployee->save();
            DB::commit();
            redirect()->back()->with('success', 'Supply Office Employee successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Supply Office Employee is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }


    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            SupplyOfficeEmployee::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Supply Office Employee successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Supply Office Employee is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }
}
