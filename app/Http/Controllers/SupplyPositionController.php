<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupplyPosition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplyPositionController extends Controller
{
    //
    public function all(){
        $supply_positions = SupplyPosition::all();
        return view('so-dashboard/manage-supply-position')
            ->with('supply_positions', $supply_positions);
    }

    public function post_add(Request $request)
    {
        try {

            
        DB::beginTransaction();
            $new_position = new SupplyPosition();
            $new_position->name = $request->name;
            $new_position->type = $request->type;
            $new_position->added_by = Auth::user()->id;
            $new_position->save();
            DB::commit();

            return redirect()->back()->with('success', 'Supplier position saved.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['Something went wrong! Supplier position is not saved. Please contact website administrator.']);
        }
    }
    public function get($position_id)
    {
        $position = SupplyPosition::find($position_id);

        return response()->json($position, 200);
    }
    public function update(Request $request, $position_id)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors);
        }


        $UpdatePosition = SupplyPosition::find($position_id);
        DB::beginTransaction();
        try {
        $UpdatePosition->name = $request->name;
        $UpdatePosition->type = $request->type; 
        $UpdatePosition->save();

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

    public function delete_single($position_id)
    {
        $getSupplyPosition = SupplyPosition::find($position_id);
        DB::beginTransaction();
        try {
            $getSupplyPosition->is_delete = 1;
            $getSupplyPosition->save();
            DB::commit();
            redirect()->back()->with('success', 'Supply Position successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Supply Positions is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            SupplyPosition::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Supply Position successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Supply Position is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }
}