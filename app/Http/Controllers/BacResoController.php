<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CanvassAbstract;
use App\Models\CanvassAbstractItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Company;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class BacResoController extends Controller
{
    //
    public function bac_reso_list()
    {
        $bac_reso = Company::whereHas('canvass_abstract', function ($query) {
            $query
                ->where('year', '=', getPpmpYear())
                ->where('is_delete', '=', 0);
        })
            ->get();

        return view('po-dashboard/all-bac-reso')
            ->with('bac_reso', $bac_reso);
    }

    public function add_new()
    {
        $company_list = Company::whereNotIn('id', function ($query) {
            $query->select('companies_id')->from('canvass_abstracts')->where('year', '=', Auth::user()->ppmp_year);
        })->get();

        return view('po-dashboard/add-bac-reso')
            ->with('company_list', $company_list);
    }

    public function prepare_bac(Request $request)
    {
        $step = 0;
        $companyId = 0;

        if ($request->has('step')) {
            $step = is_numeric($request->get('step')) ? intval($request->get('step')) : 0;
        }

        if ($request->has('cId')) {
            $companyId = is_numeric($request->get('cId')) ? intval($request->get('cId')) : 0;
        }

        if ($step === 1) {
            return view('po-dashboard/bac-step-1');
        } elseif ($step === 2) {
            $company_list = Company::all();
            return view('po-dashboard/bac-step-2')->with('company_list', $company_list);
        } elseif ($step === 3 && $companyId !== 0) {
            $company_quotations = Company::where('id', '=', $companyId)
                ->whereHas('quotations', function ($query) {
                    $query->where('year', '=', getPpmpYear());
                })
                ->whereDoesntHave('canvass_abstract', function ($query) {
                    $query->where('year', '=', getPpmpYear());
                })
                ->whereHas('quotations', function ($query) {
                    $query->where('year', '=', getPpmpYear());
                })
                ->with(['quotations.items.pr_item' => function ($query) {
                    $query->doesntHave('quotations.canvass_abstract_item');
                }, 'quotations.items.pr_item.ppmp.item_detail.unit', 'quotations.items', 'quotations.items.pr_item.pr'])
                ->first();
            try {

                // return response()->json($company_quotations, 200);
                return view('po-dashboard/bac-step-3')
                    ->with('company_quotations', $company_quotations);
            } catch (Throwable $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Cannot process your request.'
                ], 500);
            }
            return "STEP 3 and Company ID: " . $companyId;
        }

        endcheck:
        return redirect()->route('login');
    }

    public function create_bac(Request $request)
    {
        $request->validate([
            'abcVal' => 'required|numeric|min:1|max:1000000',
            'companyId' => 'required|numeric',
            'items' => 'required'
        ]);

        if (!is_array(json_decode(json_encode($request->items)))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid values. Please reload the page.'
            ], 400);
        }

        $abc = $request->abcVal;
        $companies_id = $request->companyId;
        $items = json_decode(json_encode($request->items));

        try {
            DB::beginTransaction();
            $newCanvass = new CanvassAbstract();
            $newCanvass->companies_id = $companies_id;
            $newCanvass->year = Auth::user()->ppmp_year;
            $newCanvass->abc = $abc;
            $newCanvass->added_by = Auth::user()->id;
            $newCanvass->save();
            foreach ($items as $i) {
                $newCanvassItem = new CanvassAbstractItem();
                $newCanvassItem->canvass_abstracts_id = $newCanvass->id;
                $newCanvassItem->quotation_items_id = $i;
                $newCanvassItem->save();
            }
            DB::commit();
            $success = 'Record successfully saved!';
            back()->with('success', $success);
            return response()->json([
                'success' => true,
                'message' => $success
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Record is not saved. Please contact website adminstrator.'
            ], 400);
        }
    }

    public function view_bac($company_id)
    {
        $bac_record = CanvassAbstract::where('companies_id', '=', $company_id)
            ->where('year', '=', getPpmpYear())
            ->with(['company', 'items.quotation_item.pr_item.ppmp.milestones', 'items.quotation_item.pr_item.ppmp.item_detail.unit'])
            ->first();

        // $companies = Company::whereIn('id');
        $pr_items_id = [];
        foreach ($bac_record->items as $item) {
            array_push($pr_items_id, $item->quotation_item->pr_item->id);
        }
        // $companies = Company::whereHas('quotations', function ($query) use ($quotation_ids) {
        //     return $query->where('year', '=', getPpmpYear())->whereHas('items', function ($query1) use ($quotation_ids) {
        //         $query1->whereIn('id', $quotation_ids);
        //     });
        // })
        //     ->get();
        // $companies = Company::whereIn('id', )
        // $canvasses = CanvassAbstract::with(['items.quotation_item.pr_item' => function ($query) use ($quotation_ids) {
        //     $query->whereIn('quotation_items_id', $quotation_ids);
        // }])->get();

        $companies = Company::whereHas('quotations.items.pr_item', function ($query) use ($pr_items_id) {
            $query->whereIn('id', $pr_items_id);
        })
            ->get();
        // return response()->json($companies);
        return view('po-dashboard/view-bac')
            ->with('bac_record', $bac_record)
            ->with('companies', $companies);
    }

    public function test()
    {
        return Company::with(['canvass_abstract'])->get();
    }
}
