<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\SupplyPosition;
use App\Models\SupplyEndUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SupplyEndUserController extends Controller
{
    //
    public function all()
    {
        $branches = Branch::where('type', '=', 'CAMPUS')->get();
        $positions = SupplyPosition::where('type', '=', 'END_USER')->get();
        $end_users = SupplyEndUser::with(['branch', 'position'])->get();
        return view('so-dashboard/manage-end-user')
            ->with('branches', $branches)
            ->with('positions', $positions)
            ->with('end_users', $end_users);
    }



    public function post_add(Request $request)
    {
        try {
            $nameVal = 'required|regex:/^[\pL\s\-]+$/u';

            $request->validate([
                'first_name' => $nameVal,
                'middle_name' => $nameVal,
                'last_name' => $nameVal,
                'college' => 'required|exists:branches,id',
                'position' => 'required|exists:supply_positions,id'
            ]);
            
            DB::beginTransaction();
            $new_end_user = new SupplyEndUser();
            $new_end_user->first_name = $request->first_name;
            $new_end_user->middle_name = $request->middle_name;
            $new_end_user->last_name = $request->last_name;
            $new_end_user->branches_id = $request->college;
            $new_end_user->supply_positions_id = $request->position;
            $new_end_user->added_by = Auth::user()->id;
            $new_end_user->save();
            DB::commit();

            return redirect()->back()->with('success', 'End user saved.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['Something went wrong! End user is not saved. Please contact website administrator.']);
        }
    }

    public function get($enduser_id)
    {
        $enduser = SupplyEndUser::find($enduser_id);

        return response()->json($enduser, 200);
    }

    public function update(Request $request, $enduser_id)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => ['required'],
            'middle_name' => ['required'],
            'last_name' => ['required'],
            'college' => ['required'],
            'position' => ['required']
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors);
        }


        $UpdateEnduser = SupplyEndUser::find($enduser_id);
        DB::beginTransaction();
        try {

        $UpdateEnduser->first_name = $request->first_name;
        $UpdateEnduser->middle_name = $request->middle_name;
        $UpdateEnduser->last_name = $request->last_name; 
        $UpdateEnduser->branches_id = $request->college; 
        $UpdateEnduser->supply_positions_id = $request->position;        
        $UpdateEnduser->save();

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
        $getSupplyEndUser = SupplyEndUser::find($branch_id);
        DB::beginTransaction();
        try {
            $getSupplyEndUser->is_delete = 1;
            $getSupplyEndUser->save();
            DB::commit();
            redirect()->back()->with('success', 'Supply End User successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Supply End User is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            SupplyEndUser::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Supply End User successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Supply End User is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    
}
