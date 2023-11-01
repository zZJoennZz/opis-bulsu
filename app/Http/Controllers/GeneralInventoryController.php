<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransactionItem;
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
            return $reportContent;
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function print_par($snapshot_id)
    {
        $snapShot = ReportSnapShot::find($snapshot_id);
        return view('so-dashboard.print-gi-par')
            ->with('snapShot', $snapShot);
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
}
