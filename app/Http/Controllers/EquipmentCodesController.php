<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EquipmentCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EquipmentCodesController extends Controller
{
    //
    public function all(){
        $equipment_codes = EquipmentCode::all();
        return view('so-dashboard/manage-equipment-code')
            ->with('equipment_codes', $equipment_codes);
    }

    public function post_add(Request $request)
    {
        try {

        DB::beginTransaction();

            $new_equipment_code = new EquipmentCode();
            $new_equipment_code->equipment_code = $request->equipment_code;
            $new_equipment_code->description = $request->description;
            $new_equipment_code->added_by = Auth::user()->id;
            $new_equipment_code->save();
            DB::commit();

            return redirect()->back()->with('success', 'Equipment Code has been saved.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['Something went wrong! Equipment Code is not saved. Please contact website administrator.']);
        }
    }
    public function get($equipment_code_id)
    {
        $equipment_code = EquipmentCode::find($equipment_code_id);

        return response()->json($equipment_code, 200);
    }

    public function update(Request $request, $equipment_code_id)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors);
        }


        $UpdateEquipmentCode = EquipmentCode::find($equipment_code_id);
            DB::beginTransaction();
            try {
            $UpdateEquipmentCode->equipment_code = $request->equipment_code;
            $UpdateEquipmentCode->description = $request->description; 
            $UpdateEquipmentCode->save();

            DB::commit();

            session(['success' => 'Supply Position successfully updated!']);
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

    public function delete_single($equipment_code_id)
    {
        $getEquipmentCode = EquipmentCode::find($equipment_code_id);
        DB::beginTransaction();
        try {
            $getEquipmentCode->is_delete = 1;
            $getEquipmentCode->save();
            DB::commit();
            redirect()->back()->with('success', 'Equipment Code successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Equipment Code is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            EquipmentCode::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Equipment Code successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Equipment Code is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }


}
