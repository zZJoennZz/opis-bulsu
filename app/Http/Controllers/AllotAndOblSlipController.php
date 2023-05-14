<?php

namespace App\Http\Controllers;

use App\Models\AllotAndOblSlip;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AllotAndOblSlipController extends Controller
{
    //
    public function all()
    {
        $alobs = AllotAndOblSlip::whereHas('purchase_order', function ($builder) {
            $builder->where('year', getPpmpYear());
        })
            ->where('is_delete', 0)
            ->get();

        return view('po-dashboard/all-alobs')
            ->with('alobs', $alobs);
    }

    public function view($id)
    {
        $alobs = AllotAndOblSlip::where('id', $id)
            ->whereHas('purchase_order', function ($builder) {
                $builder->where('year', getPpmpYear());
            })
            ->first();

        if ($alobs === null) {
            return redirect()->route('alobs.all')->withErrors(['ALOBS record not found.']);
        }

        $bo_users = User::with(['profile'])->where('account_type', 'BUDGET_OFFICE')->get();

        return view('po-dashboard/view-alobs')
            ->with('alobs', $alobs)
            ->with('bo_users', $bo_users);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'budget_officer' => 'exists:users,id',
        ]);
        try {
            DB::beginTransaction();
            $alobs = AllotAndOblSlip::find($id);
            $alobs->budget_officer_id = $request->budget_officer;
            $alobs->is_draft = 0;
            $alobs->save();
            DB::commit();
            return redirect()->route('alobs.all')->with('success', 'ALOBS successfully processed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. Cannot save ALOBS.']);
        }
    }

    public function print($id)
    {
        try {
            $alobs = AllotAndOblSlip::where('id', $id)
                ->whereHas('purchase_order', function ($builder) {
                    $builder->where('year', getPpmpYear());
                })
                ->with(['budget_office.profile.position', 'purchase_order.bac_reso.abstract_of_canvass.pr.pr_items.ppmp'])
                ->first();
            if ($alobs === null) {
                return redirect()->route('alobs.all')->withErrors(['Invalid ALOBS record.']);
            }
            return view('po-dashboard/print-alobs')
                ->with('alobs', $alobs);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Something went wrong. Please try again or contact web developer.']);
        }
    }
}
