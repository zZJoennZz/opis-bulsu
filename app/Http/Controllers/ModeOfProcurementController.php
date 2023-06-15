<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModeOfProcurement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;


class ModeOfProcurementController extends Controller
{
    //
    public function all()
    {
        $allModeProcurement = ModeOfProcurement::all();
        return view('po-dashboard/mode-of-procurement-list')
            ->with('mode_procurements', $allModeProcurement);
    }

    public function add(Request $request)
    {
        $newModeProcurement = new ModeOfProcurement();
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'name' => ['required', 'unique:mode_of_procurements,name'],
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors);
            }

            $newModeProcurement->name = $request->name;
            $newModeProcurement->added_by = Auth::user()->id;
            $newModeProcurement->save();
            $ModeProcurement = ModeOfProcurement::all();
            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Mode of Procurement successfully added!')
                ->with('mode_procurements', $ModeProcurement);
        } catch (Throwable $e) {
            DB::rollBack();
            $ModeProcurement = ModeOfProcurement::all();
            return redirect()->back()
                ->withErrors(['Something went wrong! Mode of Procurement not added.'])
                ->with('mode_procurements', $ModeProcurement);
        }
    }

    
    public function get($modeprocurement_id)
    {
        $modeprocurement = ModeOfProcurement::find($modeprocurement_id);
        return response()->json($modeprocurement , 200);
    }

    public function update(Request $request, $modeprocurement_id)
    {
        $getModeProcurement = ModeOfProcurement::find($modeprocurement_id);
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => [
                    'required', 
                    Rule::unique('mode_of_procurements', 'name')->ignore($getModeProcurement)
                ],
                
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors);
            }

            $getModeProcurement->name = $request->name;
            $getModeProcurement->save();

            DB::commit();

            session(['success' => 'Mode of Procurement successfully updated!']);
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

    public function delete_single($modeprocurement_id)
    {
        $getModeProcurement = ModeOfProcurement::find($modeprocurement_id);
        DB::beginTransaction();
        try {
            $getModeProcurement->is_delete = 1;
            $getModeProcurement->save();
            DB::commit();
            redirect()->back()->with('success', 'Mode of Procurement successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Mode of Procurement is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            ModeOfProcurement::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Mode of Procurement successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Mode of Procurement is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

}
