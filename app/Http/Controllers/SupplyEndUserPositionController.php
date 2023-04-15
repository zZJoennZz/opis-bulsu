<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyEndUserPositions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplyEndUserPositionController extends Controller
{
    //
    public function all(){
        $supply_enduser_positions = SupplyEndUserPositions::all();
        return view('so-dashboard/manage-end-user-position')
            ->with('supply_enduser_positions', $supply_enduser_positions);
    }

    public function post_add(Request $request)
    {
        try {

        DB::beginTransaction();

            $new_enduser_position = new SupplyEndUserPositions();
            $new_enduser_position->position_name = $request->position_name;
            $new_enduser_position->added_by = Auth::user()->id;
            $new_enduser_position->save();
            DB::commit();

            return redirect()->back()->with('success', 'Position has been saved.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['Something went wrong! Position is not saved. Please contact website administrator.']);
        }
    }
    public function get($enduser_position_id)
    {
        $equipment_code = SupplyEndUserPositions::find($enduser_position_id);

        return response()->json($equipment_code, 200);
    }

    public function update(Request $request, $enduser_position_id)
    {
        $validator = Validator::make($request->all(), [
            'position_name' => ['required', 'string', 'max:255'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors);
        }


        $UpdateEquipmentCode = SupplyEndUserPositions::find($enduser_position_id);
            DB::beginTransaction();
            try {
            $UpdateEquipmentCode->position_name = $request->position_name;
            $UpdateEquipmentCode->save();

            DB::commit();

            session(['success' => 'Position successfully updated!']);
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
    public function delete_single($enduser_position_id)
    {
        $getSupplyPosition = SupplyEndUserPositions::find($enduser_position_id);
        DB::beginTransaction();
        try {
            $getSupplyPosition->is_delete = 1;
            $getSupplyPosition->save();
            DB::commit();
            redirect()->back()->with('success', 'Position successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Position is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            SupplyEndUserPositions::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Position successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Position is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }
}
