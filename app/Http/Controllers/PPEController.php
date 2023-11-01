<?php

namespace App\Http\Controllers;

use App\Models\EquipmentCode;
use App\Models\InventoryTransactionItem;
use App\Models\ReportSnapShot;
use App\Models\SupplyEndUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PPEController extends Controller
{
    //
    public function index()
    {
        $endUsers = SupplyEndUser::all();
        $eqCodes = EquipmentCode::all();
        $reports = ReportSnapShot::where('report', 'phy-ppe')
            ->get();

        return view('so-dashboard.physical-ppe')
            ->with('endUsers', $endUsers)
            ->with('eqCodes', $eqCodes)
            ->with('reports', $reports);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'end_user' => 'required|exists:supply_end_users,id',
            'ppe_type' => 'required|exists:equipment_codes,id',
        ], [
            'end_user.required' => 'End user field is required.',
            'ppe_type.required' => 'PPE type field is required.',
        ]);

        try {
            DB::beginTransaction();
            $endUser = SupplyEndUser::where('id', $request->end_user)
                ->with(['branch', 'position'])
                ->first();

            $eqCode = EquipmentCode::find($request->ppe_type);

            $items = InventoryTransactionItem::whereHas('properties', function ($builder1) use ($request) {
                $builder1->whereHas('current_owners', function ($builder2) use ($request) {
                    $builder2->where('supply_end_users_id', $request->end_user);
                });
            })
                ->with(['properties' => function ($builder1) use ($request) {
                    $builder1->where('is_available', true)->whereHas('current_owners', function ($builder2) use ($request) {
                        $builder2->where('supply_end_users_id', $request->end_user);
                    });
                }, 'bac_reso_item.quotation.pr_item.ppmp.item_detail.unit', 'transaction', 'equipment_code'])
                ->where('unit_price', '>=', 50000)
                ->where('equipment_codes_id', $request->ppe_type)
                ->get();

            $reportToSave = [
                "endUser" => $endUser,
                "eqCode" => $eqCode,
                "items" => $items,
                "inventoryCommitteeChair" => $request->inventoryCommitteeChair ?? getSettingValue('inventory_committee_chair'),
                "inventoryCommitteeMembers" => $request->inventoryCommitteeMembers ?? getSettingValue('inventory_committee_members'),
                "headOfAgency" => $request->headOfAgency ?? getSettingValue('university_president'),
                "generated_by" => Auth::user()->id,
            ];

            $newReport = new ReportSnapShot([
                'report' => 'phy-ppe',
                'content' => json_encode($reportToSave),
            ]);

            $newReport->save();
            DB::commit();
            return redirect()->back()->with('success', 'Report generated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. Cannot generate report!']);
        }
    }

    public function print($id)
    {
        $report = ReportSnapShot::find($id);

        return view('so-dashboard.print-physical-ppe')
            ->with('report', $report);
    }
}
