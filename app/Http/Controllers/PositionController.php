<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class PositionController extends Controller
{
    
    public function all()
    {
        $allPosition = Position::all();
        return view('po-dashboard/position-list')
            ->with('positions', $allPosition);
    }

    public function add(Request $request)
    {
        $newPosition = new Position();
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'description' => ['required', 'unique:positions,description'],
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors);
            }


            $newPosition->description = $request->description;
            $newPosition->added_by = Auth::user()->id;
            $newPosition->save();
            $Positions = Position::all();

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Position successfully added!')
                ->with('positions', $Positions);
        } catch (Throwable $e) {
            DB::rollBack();
            $Positions = Position::all();
            return redirect()->back()
                ->withErrors(['Something went wrong! Position not added.'])
                ->with('positions', $Positions);
        }
    }

    public function get($position_id)
    {
        $position = Position::find($position_id);

        return response()->json($position, 200);
    }

    public function update(Request $request, $position_id)
    {
        $getPosition = Position::find($position_id);
        DB::beginTransaction();

        try {

            $validator = Validator::make($request->all(), [
                'description' => [
                    'required', 
                    Rule::unique('positions', 'description')->ignore($getPosition)
                ],
                
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors);
            }


            $getPosition->description = $request->description;
            $getPosition->save();

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

    public function delete_single($position_id)
    {
        $getPosition = Position::find($position_id);
        DB::beginTransaction();
        try {
            $getPosition->is_delete = 1;
            $getPosition->save();
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
            Position::whereIn('id', $request->id)->update(["is_delete" => 1]);
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
