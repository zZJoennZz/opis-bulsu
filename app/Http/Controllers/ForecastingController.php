<?php

namespace App\Http\Controllers;

use App\Models\ItemDetail;
use App\Models\ItemForecasting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForecastingController extends Controller
{
    //
    public function item_forecasting_index()
    {
        // $alpha = 0.1; // Smoothing factor between 0 and 1
        // $data = [34, 59, 37.04, 37.83, 38.87, 40.69, 55, 44.20, 46.56];

        try {
            $all_items = ItemDetail::all();
            return view('so-dashboard.item-forecasting-dashboard')
                ->with('all_items', $all_items);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.show');
        }
    }

    public function generate_forecasting_single($id)
    {
        try {
            $item = ItemDetail::find($id);

            return view('so-dashboard.generate-item-forecast')
                ->with('item', $item);
        } catch (\Exception $e) {
            return redirect()->route('dashboard.show');
        }
    }

    public function generate_forecasting(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $request->validate([
                'alpha' => 'min:1,max:10|required|numeric',
                'group' => 'in:MONTH,YEAR|required',
                'from_date' => 'required|date',
                'to_date' => 'required|date'
            ]);

            $start = Carbon::parse($request->from_date);
            $end = Carbon::parse($request->to_date);

            if (($request->group === "MONTH" && $start->diffInMonths($end) <= 1) || ($request->group === "YEAR" && $start->diffInYears($end) <= 1)) {
                return  redirect()->back()->withErrors(['Please select a date range that has at least 2 groups of data!']);
            }

            $itemDetail = ItemDetail::with([
                'ppmp.pr_item.quotations.bac_reso_item.supply_inventory_item.transaction' => function ($query) use ($request) {
                    $query->whereBetween('date_issued', [$request->from_date, $request->to_date]);
                }
            ])
                ->where('id', $id)
                ->first();

            if ($itemDetail) {
                $supplyInventoryItems = [];

                foreach ($itemDetail->ppmp as $ppmp) {
                    foreach ($ppmp->pr_item->quotations as $quotation) {
                        if ($quotation->bac_reso_item && $quotation->bac_reso_item->supply_inventory_item) {
                            $supplyInventoryItems[] = $quotation->bac_reso_item->supply_inventory_item;
                        }
                    }
                }

                // Initialize an empty array for grouped data
                $groupedByMonth = [];

                // Loop through each item
                foreach ($supplyInventoryItems as $item) {
                    if ($item["transaction"]) {
                        // Extract the date_acquired
                        $dateAcquired = $item['transaction']['date_acquired'];
                        // Format the date to 'Y-m' (e.g., '2024-08')
                        $monthYear = date('Y-m', strtotime($dateAcquired));

                        // Initialize the month array if it doesn't exist
                        if (!isset($groupedByMonth[$monthYear])) {
                            $groupedByMonth[$monthYear] = [];
                        }

                        // Add the item to the corresponding month array
                        $groupedByMonth[$monthYear][] = $item;
                    }
                }

                ksort($groupedByMonth);
                $organizedData = [];
                if ($request->group === "MONTH") {
                    $dates = $this->getMonthsBetweenDates($request->from_date, $request->to_date);

                    // Iterate over each date in the main group
                    foreach ($dates as $date) {
                        // Initialize the array for each month-year
                        $organizedData[$date] = [];

                        // Filter data based on the month-year
                        foreach ($groupedByMonth as $key => $items) {
                            if ($key === $date) {
                                $qty = 0;
                                foreach ($items as $item) {
                                    $qty += $item->quantity;
                                }
                                $organizedData[$date] = $qty;
                            }
                        }
                    }
                } elseif ($request->group === "YEAR") {
                    $dates = $this->getYearsBetweenDates($request->from_date, $request->to_date);

                    // Iterate over each date in the main group
                    foreach ($dates as $date) {
                        // Initialize the array for each month-year
                        $organizedData[$date] = [];
                        // Filter data based on the month-year
                        foreach ($groupedByMonth as $key => $items) {
                            if (substr($key, 0, 4) === $date) {
                                $qty = 0;
                                foreach ($items as $item) {
                                    $qty += $item->quantity;
                                }
                                $organizedData[$date] = $qty;
                            }
                        }
                    }
                } else {
                    DB::rollBack();
                    return redirect()->back()->withErrors(["Invalid submission!"]);
                }

                $alpha = $request->alpha * 0.1;

                $preparedData = prepareData($organizedData);
                $result = exponentialSmoothing($preparedData, $alpha);

                $new_forecast = new ItemForecasting();
                $new_forecast->item_details_id = $id;
                $new_forecast->alpha = $alpha;
                $new_forecast->group = $request->group;
                $new_forecast->from_date = $request->from_date;
                $new_forecast->to_date = $request->to_date;
                $new_forecast->data = json_encode($result);

                $new_forecast->save();
                DB::commit();

                // return $organizedData;
                return redirect()->route('if.view', ['item_details_id' => $id, 'id' => $new_forecast->id]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dashboard.show');
        }
    }

    public function view_forecast($item_details_id, $id)
    {
        try {
            $item_forecast = ItemForecasting::find($id);
            return view("so-dashboard.view-forecast")
                ->with('item_forecast', $item_forecast);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(["Cannot access forecast at this time. Please try again or contact web administrator."]);
        }
    }

    public function about_the_model() {
        return view("so-dashboard.about-the-model");
    }

    private function getMonthsBetweenDates($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $months = [];

        // Loop through each month between the two dates
        while ($start->lessThanOrEqualTo($end)) {
            $months[] = $start->format('Y-m');
            $start->addMonth(); // Move to the next month
        }

        return $months;
    }

    private function getYearsBetweenDates($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $years = [];

        // Loop through each year between the two dates
        while ($start->lessThanOrEqualTo($end)) {
            $years[] = $start->format('Y');
            $start->addYear(); // Move to the next year
        }

        // Return unique years
        return $years;
    }
}
