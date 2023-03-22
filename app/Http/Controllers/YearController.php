<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class YearController extends Controller
{
    public function update_year(Request $request)
    {
        
        DB::beginTransaction();
        try {
            if (Auth::user()->account_type === 'admin' || Auth::user()->account_type === 'PROCUREMENT_OFFICE') { 
                User::where('account_type', '<>', '')->update(['ppmp_year' => $request->ppmp_year]);
            } else {
                $user = User::find(Auth::user()->id);
                $user->ppmp_year = $request->ppmp_year;
                $user->save();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Year saved!');
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Your PPMP year does not change. Please try again or contact website administrator.']);
        }
    }
}
