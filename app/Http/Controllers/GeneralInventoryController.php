<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransactionItem;
use App\Models\InventoryTransactionItemProperty;
use App\Models\ReportSnapShot;
use App\Models\SupplyEndUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralInventoryController extends Controller
{
    //
    public function par()
    {
        $endUsers = SupplyEndUser::all();
        $reports = ReportSnapShot::where('report', 'gi-par')
            ->get();

        return view('so-dashboard.gi-par')
            ->with('endUsers', $endUsers)
            ->with('reports', $reports);
    }

    public function generate_par(Request $request)
    {
        $request->validate([
            'end_user_id' => 'required|exists:supply_end_users,id',
        ], [
            'end_user_id.required' => 'End user is required.',
            'end_user_id.exists' => 'Selected end user does not exist.'
        ]);

        try {
            DB::beginTransaction();
            $items = InventoryTransactionItem::whereHas('properties', function ($builder1) use ($request) {
                $builder1->whereHas('current_owners', function ($builder2) use ($request) {
                    $builder2->where('supply_end_users_id', $request->end_user_id);
                });
            })
                ->with(['properties' => function ($builder1) use ($request) {
                    $builder1->where('is_available', true)->whereHas('current_owners', function ($builder2) use ($request) {
                        $builder2->where('supply_end_users_id', $request->end_user_id);
                    });
                }, 'bac_reso_item.quotation.pr_item.ppmp.item_detail.unit', 'transaction'])
                ->whereHas('transaction', function ($builder) {
                    $builder->where('type', 'PAR');
                })
                ->get();

            $reportContent = [
                "items" => $items,
                "end_user" => SupplyEndUser::where('id', $request->end_user_id)->with(['position', 'branch'])->first(),
            ];

            $newReportSnapshot = new ReportSnapShot([
                'report' => 'gi-par',
                'content' => json_encode($reportContent),
            ]);

            $newReportSnapshot->save();

            DB::commit();
            return redirect()->back()->with('success', 'Report generated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. Report not generated.']);
        }
    }

    public function print_par($snapshot_id)
    {
        $snapShot = ReportSnapShot::find($snapshot_id);
        return view('so-dashboard.print-gi-par')
            ->with('snapShot', $snapShot);
    }

    public function delete($snapshot_id)
    {
        try {
            DB::beginTransaction();
            $getReportSnapshot = ReportSnapShot::find($snapshot_id);
            $getReportSnapshot->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Report deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. Report is not deleted.']);
        }
    }

    public function ics()
    {
        $endUsers = SupplyEndUser::all();
        $reports = ReportSnapShot::where('report', 'gi-ics')
            ->get();

        return view('so-dashboard.gi-ics')
            ->with('endUsers', $endUsers)
            ->with('reports', $reports);
    }

    public function generate_ics(Request $request)
    {
        $request->validate([
            'end_user_id' => 'required|exists:supply_end_users,id',
        ], [
            'end_user_id.required' => 'End user is required.',
            'end_user_id.exists' => 'Selected end user does not exist.'
        ]);

        try {
            DB::beginTransaction();
            $items = InventoryTransactionItem::whereHas('properties', function ($builder1) use ($request) {
                $builder1->whereHas('current_owners', function ($builder2) use ($request) {
                    $builder2->where('supply_end_users_id', $request->end_user_id);
                });
            })
                ->with(['properties' => function ($builder1) use ($request) {
                    $builder1->where('is_available', true)->whereHas('current_owners', function ($builder2) use ($request) {
                        $builder2->where('supply_end_users_id', $request->end_user_id);
                    });
                }, 'bac_reso_item.quotation.pr_item.ppmp.item_detail.unit', 'transaction'])
                ->whereHas('transaction', function ($builder) {
                    $builder->where('type', '<>', 'PAR');
                })
                ->get();

            $reportContent = [
                "items" => $items,
                "end_user" => SupplyEndUser::where('id', $request->end_user_id)->with(['position', 'branch'])->first(),
            ];

            $newReportSnapshot = new ReportSnapShot([
                'report' => 'gi-ics',
                'content' => json_encode($reportContent),
            ]);

            $newReportSnapshot->save();

            DB::commit();
            return $reportContent;
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function print_ics($snapshot_id)
    {
        $snapShot = ReportSnapShot::find($snapshot_id);
        return view('so-dashboard.print-gi-ics')
            ->with('snapShot', $snapShot);
    }

    public function inventory_inspection_index()
    {
        $unavailableProperties = InventoryTransactionItemProperty::where('is_available', 0)
            ->whereHas('item.equipment_code', function ($builder) {
                $builder->where('article', 'SEMI_EXPENDABLE');
            })
            ->get();
        $generatedReports = ReportSnapShot::where('report', 'iirup')
            ->select(['id', 'created_at'])
            ->get();
        return view('so-dashboard.iir-of-unserviceable-property')
            ->with('unavailableProperties', $unavailableProperties)
            ->with('generatedReports', $generatedReports);
    }

    public function inventory_inspection_generate()
    {
        try {
            DB::beginTransaction();
            $unavailableProperties = InventoryTransactionItemProperty::where('is_available', 0)
                ->whereHas('item.equipment_code', function ($builder) {
                    $builder->where('article', 'SEMI_EXPENDABLE');
                })
                ->with(['item.transaction', 'item.bac_reso_item.quotation.pr_item.ppmp.item_detail'])
                ->get();

            $newReport = new ReportSnapShot([
                'report' => 'iirup',
                'content' => json_encode($unavailableProperties),
            ]);

            $newReport->save();
            DB::commit();
            return redirect()->back()->with('success', 'Report successfully generated!');
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function print_inventory_inspection($reportId)
    {
        try {
            $report = ReportSnapShot::where('id', $reportId)
                ->where('report', 'iirup')
                ->first();

            if ($report === null) {
                return redirect()->to('/inventory-and-inspection-report-of-unserviceable-property')->withErrors(['No reports available. Invalid ID.']);
            }

            return view('so-dashboard.print-iirup')
                ->with('report', $report);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['Something went wrong. Please try again!']);
        }
    }
}
