<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SourceOfFund;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;

class SourceofFundsController extends Controller
{
    public function all()
    {
        $allSourceOfFund = SourceOfFund::all();
        return view('po-dashboard/source-of-fund-list')
            ->with('source_of_funds', $allSourceOfFund);
    }

    public function add(Request $request)
    {
        $newSourceOfFund = new SourceOfFund();
        DB::beginTransaction();
        try {

            $validator = Validator::make($request->all(), [
                'source_of_fund' => ['required', 'unique:source_of_funds,source_of_fund'],
                'description' => ['required']
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors);
            }

            $newSourceOfFund->source_of_fund = $request->source_of_fund;
            $newSourceOfFund->description = $request->description;
            $newSourceOfFund->added_by = Auth::user()->id;
            $newSourceOfFund->save();
            $SourceOfFund = SourceOfFund::all();
            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Source of Fund successfully added!')
                ->with('source_of_fund', $SourceOfFund);
        } catch (Throwable $e) {
            DB::rollBack();
            $SourceOfFund = SourceOfFund::all();
            return redirect()->back()
                ->withErrors(['Something went wrong! Source Of Fund not added.'])
                ->with('$source_of_fund', $SourceOfFund);
        }
    }

    public function get($source_of_fund_id)
    {
        $source_of_fund = SourceOfFund::find($source_of_fund_id);

        return response()->json($source_of_fund, 200);
    }

    public function update(Request $request, $source_of_fund_id)
    {
        $getSourceOfFund = SourceOfFund::find($source_of_fund_id);
        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'description' => ['required'],
                'source_of_fund' => [
                    'required', 
                    Rule::unique('source_of_funds', 'source_of_fund')->ignore($getSourceOfFund)
                ],
                
                
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors);
            }
            $getSourceOfFund->description = $request->description;
            $getSourceOfFund->source_of_fund = $request->source_of_fund;
            $getSourceOfFund->save();

            DB::commit();

            session(['success' => 'Source of Fund successfully updated!']);
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

    public function delete_single($source_of_fund_id)
    {
        $getSourceOfFund = SourceOfFund::find($source_of_fund_id);
        DB::beginTransaction();
        try {
            $getSourceOfFund->is_delete = 1;
            $getSourceOfFund->save();
            DB::commit();
            redirect()->back()->with('success', 'Source of Fund successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Source of Fund is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            SourceOfFund::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Source Of Fund successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Source Of Fund is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }
}
