<?php

namespace App\Http\Controllers;

use App\Models\BACResoItem;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\InspectionAndAcceptance;
use App\Models\InventoryCustodian;
use App\Models\SourceOfFund;
use App\Models\SupplyOfficeEmployee;
use App\Models\SupplyEndUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InventoryCustodianController extends Controller
{
    //
    public function add()
    {
        $iars = InspectionAndAcceptance::whereHas('purchase_order', function ($builder) {
            $builder->where('year', getPpmpYear());
        })->get();

        $supply_employees = SupplyOfficeEmployee::where('is_delete', 0)->get();
        $supply_end_user = SupplyEndUser::where('is_delete', 0)->get();
        $companies = Company::where('is_delete', 0)->get();
        $source_of_fund = SourceOfFund::where('is_delete', 0)->get();

        return view('so-dashboard/add-ic')
            ->with('iars', $iars)
            ->with('supply_employees', $supply_employees)
            ->with('supply_end_user', $supply_end_user)
            ->with('companies', $companies)
            ->with('source_of_fund', $source_of_fund);
    }

    public function store(Request $request)
    {
        $request->validate([
            'date_acquired' => 'required|date',
            'inspection_and_acceptances_id' => 'required|exists:inspection_and_acceptances,id',
            'b_a_c_reso_items_id' => 'required|exists:b_a_c_reso_items,id',
            'received_by' => 'required|exists:supply_end_users,id',
            'received_from' => 'required|exists:supply_office_employees,id',
            'date_issued' => 'required|date',
            'source_of_fund' => 'required|exists:source_of_funds,id',
            'fund_cluster_series' => 'required|max:255',
            'serial_number' => 'required',
        ]);
        try {
            DB::beginTransaction();

            $latest_ics = InventoryCustodian::latest()
                ->first();

            $ics_num_ctr = $latest_ics === null ? 1 : intval(substr($latest_ics->ics_number, 8, 4)) + 1;

            $ics_number = sprintf(
                '%s-%s-%s',
                getPpmpYear(),
                date('m'),
                str_pad($ics_num_ctr, 4, '0', STR_PAD_LEFT)
            );

            $get_company_id = BACResoItem::with(['quotation.quotation.company'])->where('id', $request->b_a_c_reso_items_id)->first();

            $new_ics = new InventoryCustodian();
            $new_ics->ics_number = $ics_number;
            $new_ics->date_acquired = $request->date_acquired;
            $new_ics->inspection_and_acceptances_id = $request->inspection_and_acceptances_id;
            $new_ics->b_a_c_resos_items_id = $request->b_a_c_reso_items_id;
            $new_ics->serial_number = $request->serial_number;
            $new_ics->received_from = $request->received_from;
            $new_ics->received_by = $request->received_by;
            $new_ics->date_issued = $request->date_issued;
            $new_ics->source_of_funds_id = $request->source_of_fund;
            $new_ics->delivered_by = $get_company_id->quotation->quotation->company->id;
            $new_ics->fund_cluster_year = getPpmpYear();
            $new_ics->fund_cluster_month = date('m');
            $new_ics->fund_cluster_series = $request->fund_cluster_series;
            $new_ics->added_by = Auth::user()->id;
            $new_ics->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['Something went wrong. Please try again or contact web developer.']);
        }
    }

    public function print($id)
    {
        try {
            $ics = InventoryCustodian::where('id', $id)
                ->first();

            return view('so-dashboard/print-ic')
                ->with('ics', $ics);
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->withErrors(['Something went wrong. Cannot access print.']);
        }
    }
}
