<?php

namespace App\Http\Controllers;

use App\Models\AbstractOfCanvass;
use Illuminate\Http\Request;
use App\Models\BACReso;
use App\Models\BACResoItem;
use App\Models\CanvassAbstract;
use App\Models\CanvassAbstractItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Company;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class BacResoController extends Controller
{
    //
    public function all()
    {
        $bac_resos = BACReso::where('is_delete', '=', 0)
            ->whereHas('abstract_of_canvass', function ($builder) {
                $builder->where('year', '=', getPpmpYear());
            })
            ->with(['abstract_of_canvass.pr'])
            ->get();

        // return $bac_reso;
        return view('po-dashboard/all-bac-reso')
            ->with('bac_resos', $bac_resos);
    }

    public function add()
    {
        $purchase_requests = AbstractOfCanvass::whereHas('pr', function ($builder) {
            $builder->where('year', '=', getPpmpYear())->where('is_delete', '=', 0);
        })
            ->has('pr.pr_items.quotations')
            ->with(['pr'])
            ->doesntHave('bac_reso')
            ->get();
        // return $purchase_requests;
        return view('po-dashboard/add-bac-reso')
            ->with('purchase_requests', $purchase_requests);
    }

    public function save(Request $request)
    {
        $aoc = AbstractOfCanvass::where('id', '=', $request->purchase_request)->with(['pr.requester.profile'])->first();

        //BAC Reso Number Builder
        $latest_bac_reso = BACReso::where(DB::raw('SUBSTR(b_a_c_reso_number, 1, 4)'), '=', getPpmpYear())
            ->latest()
            ->first();

        $bac_reso_ctr = $latest_bac_reso === null ? 1 : intval(substr($latest_bac_reso->b_a_c_reso_number, 5, 4)) + 1;
        // $bac_year = substr($latest_bac_reso->b_a_c_reso_number, 0, 4);

        $bac_reso_number = sprintf(
            '%s-%s',
            getPpmpYear(),
            str_pad($bac_reso_ctr, 3, '0', STR_PAD_LEFT),
        );

        // return $bac_reso_number;

        DB::beginTransaction();
        try {
            $new_bac = new BACReso();
            $new_bac->b_a_c_reso_number = $bac_reso_number;
            $new_bac->abstract_of_canvasses_id = $request->purchase_request;
            $new_bac->is_draft = 1;
            $new_bac->added_by = Auth::user()->id;
            $new_bac->save();
            DB::commit();
            return redirect()->route('bac-reso.single', ['id' => $new_bac->id])->with('success', 'BAC Resolution generated. Please complete the form!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong! BAC Resolution not generated.']);
        }
    }

    public function single($id)
    {
        $bac_reso = BACReso::with(['bac_reso_items.quotation.pr_item', 'bac_reso_items.quotation.pr_item.ppmp.item_detail.unit', 'abstract_of_canvass.pr.pr_items.quotations.bac_reso_item'])
            ->where('id', '=', $id)
            ->where('is_delete', '=', 0)
            ->first();

        $bac_reso_items = BACResoItem::where('b_a_c_resos_id', '=', $id)->select('quotation_items_id')->get();

        if ($bac_reso === null) {
            return redirect()->route('bac-reso.all')->withErrors(['BAC Resolution not found!']);
        }

        $purchase_request_items = PurchaseRequest::where('id', '=', $bac_reso->abstract_of_canvass->purchase_requests_id)->with(['pr_items.ppmp.item_detail.unit', 'pr_items.ppmp.milestones'])->first();

        $total_products = count($purchase_request_items->pr_items);

        $quotations = Quotation::whereHas('items.pr_item.pr', function ($builder) use ($bac_reso) {
            $builder->where('id', '=', $bac_reso->abstract_of_canvass->purchase_requests_id);
        })
            ->with(['items.pr_item.pr'])
            ->where('year', '=', getPpmpYear())
            ->get();

        $companies = Company::whereHas('quotations.items.pr_item.pr', function ($builder) use ($bac_reso) {
            $builder->where('id', '=', $bac_reso->abstract_of_canvass->purchase_requests_id);
        })
            ->with('quotations.items.pr_item.pr')
            ->get();
        $sel_company = [];
        $view_bac_view = 'po-dashboard/view-bac';
        if ($bac_reso->abstract_of_canvass->type === "BY_LOT") {
            $sel_company = Company::has('quotations.items.bac_reso_item')
                ->whereHas('quotations.items', function ($builder) use ($bac_reso_items) {
                    $builder->whereIn('id', $bac_reso_items);
                })
                ->get();

            // return $bac_reso_items;
            return view($view_bac_view)
                ->with('bac_reso', $bac_reso)
                ->with('purchase_request_items', $purchase_request_items)
                ->with('quotations', $quotations)
                ->with('total_count_products', $total_products)
                ->with('companies', $companies)
                ->with('sel_company', $sel_company);
        } else {
            return view($view_bac_view)
                ->with('bac_reso', $bac_reso)
                ->with('purchase_request_items', $purchase_request_items)
                ->with('quotations', $quotations)
                ->with('total_count_products', $total_products)
                ->with('companies', $companies);
        }
    }

    public function get_quotations_by_pr($pr_item_id)
    {
        try {
            $quotations = QuotationItem::where('purchase_request_items_id', '=', $pr_item_id)
                ->with('quotation.company')
                ->whereHas('quotation', function ($builder) {
                    $builder->where('year', '=', getPpmpYear());
                })
                ->doesntHave('bac_reso_item')
                ->get();

            if (count($quotations) < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot find record.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $quotations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! Cannot fetch data.'
            ], 400);
        }
    }

    public function remove_bac_reso_item($bac_reso_item_id)
    {
        DB::beginTransaction();
        try {
            $toDelete = BACResoItem::find($bac_reso_item_id);
            if ($toDelete === null) {
                return redirect()->back()->withErrors(['Record not found. No record is deleted.']);
            } else {
                $toDelete->delete();
            }
            DB::commit();
            return redirect()->back()->with('success', 'Item removed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong! Company not removed!']);
        }
    }

    public function remove_items_by_lot(Request $request)
    {
        DB::beginTransaction();

        try {
            BACResoItem::where('b_a_c_resos_id', '=', $request->b_a_c_resos_id)->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Item removed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong! Company not removed!']);
        }
    }

    public function add_bac_reso_item(Request $request)
    {
        DB::beginTransaction();
        try {
            $bac_reso = BACReso::find($request->bacId);

            $success_message = "";
            if ($bac_reso->abstract_of_canvass->type === "BY_ITEM") {
                $new_bac_item = new BACResoItem();
                $new_bac_item->b_a_c_resos_id = $request->bacId;
                $new_bac_item->quotation_items_id = $request->item;
                $new_bac_item->is_select = 1;
                $new_bac_item->save();
                $success_message = "Supplier selected for the item.";
            } else {
                $bac_reso = BACReso::with(['abstract_of_canvass.pr.pr_items'])
                    ->where('id', '=', $request->bacId)
                    ->first();

                $quotations = [];
                foreach ($bac_reso->abstract_of_canvass->pr->pr_items as $item) {
                    // echo $request->company;
                    $quotation_items = QuotationItem::where('purchase_request_items_id', '=', $item->id)->whereHas('quotation.company', function ($builder) use ($request) {
                        $builder->where('id', '=', $request->company);
                    })->first();
                    array_push($quotations, $quotation_items);
                }

                if (count($quotations) === count($bac_reso->abstract_of_canvass->pr->pr_items)) {
                    for ($i = 0; $i < count($quotations); $i++) {
                        $new_bac_item = new BACResoItem();
                        $new_bac_item->b_a_c_resos_id = $request->bacId;
                        $new_bac_item->quotation_items_id = $quotations[$i]->id;
                        $new_bac_item->is_select = 1;
                        $new_bac_item->save();
                    }
                    $success_message = "Supplier selected for the item.";
                } else {
                    redirect()->back()->withErrors(['Something went wrong. Record is not saved.']);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', $success_message);
        } catch (\Exception $e) {
            DB::rollBack();
            redirect()->back()->withErrors(['Something went wrong. Record is not saved.']);
        }
    }

    public function complete_bac_reso(Request $request)
    {
        $bac_reso = BACReso::has('bac_reso_items')->where('id', '=', $request->bac_resos_id)->get();

        if (count($bac_reso) === 0) {
            return redirect()->back()->withErrors(['Please select supplier/dealer/company.']);
        }
        DB::beginTransaction();
        try {
            $bac_rec = BACReso::find($request->bac_resos_id);
            $bac_rec->is_draft = 0;
            $bac_rec->save();
            DB::commit();

            return redirect()->back()->with('success', 'BAC Resolution created!');
        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong! BAC Resolution not created.']);
        }
    }

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

    public function get_single($bac_reso_id, $company_id)
    {
        $bac_reso = BACReso::where('id', '=', $bac_reso_id)
            ->whereHas('bac_reso_items.quotation.quotation', function ($builder) use ($company_id) {
                $builder->where('companies_id', '=', $company_id);
            })
            ->with(['abstract_of_canvass', 'bac_reso_items.quotation.pr_item.ppmp.milestones', 'bac_reso_items.quotation.pr_item.ppmp.item_detail.unit', 'bac_reso_items.quotation.quotation'])
            ->get();

        if (count($bac_reso) === 0) {
            return response()->json([
                'status' => false,
                'message' => "Data not found."
            ], 404);
        } else {
            return response()->json([
                'status' => true,
                'data' => $bac_reso
            ], 200);
        }
    }

    public function test()
    {
        return Company::with(['canvass_abstract'])->get();
    }
}
