<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class UnitController extends Controller
{
    public function all()
    {
        $allUnits = Unit::all();
        return view('po-dashboard/unit-list')
            ->with('units', $allUnits);
    }

    public function add(Request $request)
    {
        $newUnit = new Unit();
        DB::beginTransaction();
        try {
            $newUnit->uom = $request->uom;
            $newUnit->added_by = Auth::user()->id;
            $newUnit->save();
            $Unit = Unit::all();
            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Unit successfully added!')
                ->with('unit', $Unit);
        } catch (Throwable $e) {
            DB::rollBack();
            $Unit = Unit::all();
            return redirect()->back()
                ->withErrors(['Something went wrong! Unit not added.'])
                ->with('unit', $Unit);
        }
    }

    public function get($unit_id)
    {
        $unit = Unit::find($unit_id);

        return response()->json($unit, 200);
    }

    public function update(Request $request, $unit_id)
    {
        $getUnit = Unit::find($unit_id);
        DB::beginTransaction();

        try {
            $getUnit->uom = $request->uom;
            $getUnit->save();

            DB::commit();

            session(['success' => 'Unit successfully updated!']);
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

    public function delete_single($unit_id)
    {
        $getUnit = Unit::find($unit_id);
        DB::beginTransaction();
        try {
            $getUnit->is_delete = 1;
            $getUnit->save();
            DB::commit();
            redirect()->back()->with('success', 'Unit successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Unit is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            Unit::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Unit successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Unit is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }
}
