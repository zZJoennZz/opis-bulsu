<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\SupplyPosition;
use App\Models\SupplyEndUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
