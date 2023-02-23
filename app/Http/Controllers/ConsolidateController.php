<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProProManPlan;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class ConsolidateController extends Controller
{
    //

    public function index()
    {
        $getConsolidated = ProProManPlan::get()->where('is_consolidate', '=', 1)->where('year', '=', Auth::user()->ppmp_year)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1)->groupBy(function ($data) {
            return $data->item_detail;
        });

        return view('po-dashboard/view-consolidate')->with('consolidated_records', $getConsolidated);
    }

    public function consolidate()
    {
        $getConsolidated = ProProManPlan::get()->where('is_consolidate', '=', 0)->where('year', '=', Auth::user()->ppmp_year)->where('is_draft', '=', 0)->where('is_bo_approve', '=', 1)->where('is_pr_approve', '=', 1);

        $toConsolidate = [];
        foreach ($getConsolidated as $ppmp) {
            array_push($toConsolidate, $ppmp->id);
        }

        DB::beginTransaction();
        try {
            ProProManPlan::whereIn('id', $toConsolidate)->update(['is_consolidate' => 1]);
            DB::commit();
            return redirect()->route('consolidated.show')->with('success', 'Consolidation successful!');
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(["Consolidation failed. Please try again. If the problem persists, contact website administrator."]);
        }
    }

    public function reset_consolidation()
    {
        DB::beginTransaction();
        try {
            $isOkayToConsolidate = count(PurchaseRequest::where('year', '=', getPpmpYear())->get()) > 0 ? false : true;
            if ($isOkayToConsolidate) {
                ProProManPlan::where('is_consolidate', '=', 1)->where('year', '=', Auth::user()->ppmp_year)->update(['is_consolidate' => 0]);
                PurchaseRequestMode::where('year', '=', Auth::user()->ppmp_year)->update(['mode' => 'DISABLED']);
                DB::commit();
                return redirect()->route('consolidated.show')->with('success', 'Successfully reset the consolidation for the year <span class="badge bg-primary">' . Auth::user()->ppmp_year . '</span>!');
            } else {
                return redirect()->route('consolidated.show')->withErrors(['Sorry, not allowed to reset consolidated PPMP with existing PRs, quotations, BAC resolution, and purchase order.']);
            }
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(["Consolidation failed. Please try again. If the problem persists, contact website administrator."]);
        }
    }
}
