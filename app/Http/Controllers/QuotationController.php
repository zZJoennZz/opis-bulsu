<?php

namespace App\Http\Controllers;

use App\Models\CanvassAbstractItem;
use App\Models\Company;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationRequest;
use App\Models\ModeOfProcurement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class QuotationController extends Controller
{
    public function all()
    {
        $quotations = Quotation::with(['items', 'company', 'items.pr_item', 'items.pr_item.ppmp.milestones'])->where('year', '=', getPpmpYear())->get();
        $pryears = PurchaseRequest::where('year', '=', getPpmpYear())->get();
        // return $quotations;
        // return view('po-dashboard/quotation-list')->with('quotations', $quotations);
        return view('po-dashboard.quotation-list', compact('quotations', 'pryears'));
    }

    public function add()
    {
        $company_profiles = Company::all();
        // $quotation_items = QuotationItem::select('pro_pro_man_plans_id')
        //     ->whereIn('quotations_id', function ($query) {
        //         $query->select('id')->from('quotations')->where('year', '=', Auth::user()->ppmp_year);
        //     })->get();
        $pr_items = PurchaseRequestItem::select('purchase_requests_id')
            ->whereIn('purchase_requests_id', function ($query) {
                $query->select('id')->from('purchase_requests')->where('year', '=', Auth::user()->ppmp_year);
            })->get();
        $purchase_requests = PurchaseRequest::where('year', '=', Auth::user()->ppmp_year)
            ->where('is_approve', '=', 1)
            ->where('is_delete', '=', 0)
            ->where('is_draft', '=', 0)
            ->whereIn('id', $pr_items)
            ->get();

        return view('po-dashboard/add-new-quotation')
            ->with('company_profiles', $company_profiles)
            ->with('purchase_requests', $purchase_requests);
    }

    public function new_request(Request $request)
    {
        DB::beginTransaction();
        try {
            $new_quotation = new Quotation();
            $new_quotation->companies_id = $request->companies_id;
            $new_quotation->quotation_number = "na";
            $new_quotation->year = getPpmpYear();
            $new_quotation->save();
            $quote_id = $new_quotation->id;
            $find_quotation = Quotation::find($quote_id);
            $find_quotation->quotation_number = "Q" . crc32($quote_id);
            $find_quotation->save();
            foreach ($request->items as $item) {
                $new_quotation_item = new QuotationItem();
                $new_quotation_item->item_number = $item['item_number'];
                $new_quotation_item->quotations_id = $quote_id;
                $new_quotation_item->purchase_request_items_id = $item['purchase_requests_id'];
                // $ppmp = PurchaseRequestItem::where('id', '=', $item['purchase_requests_id'])->get();
                // $new_quotation_item->pro_pro_man_plans_id = $ppmp[0]->pro_pro_man_plans_id; //no need since we will connect quotation items to purchase request items instead of PPMP
                $new_quotation_item->brand_and_model_offered = $item['brand_and_model_offered'];
                $new_quotation_item->offered_unit_price = $item['offered_unit_price'];
                $new_quotation_item->save();
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Quotation request submitted.'
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Quotation request failed to submit.'
            ], 400);
        }
    }

    public function get_single($quotation_id)
    {
        try {
            $quotation = Quotation::where('id', '=', $quotation_id)
                ->with(['items.pr_item.ppmp.item_detail.unit', 'items.pr_item.ppmp.item_purpose', 'items.pr_item.ppmp.milestones', 'items.pr_item.pr', 'company'])
                ->get();
            if (count($quotation) === 0) {
                return redirect()->route('quotation.all');
            }
            return response()->json([
                'success' => true,
                'data' => $quotation,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Can\'t fetch quotation.'
            ], 400);
        }
    }

    public function get_summary()
    {
        $quotation_summaries = Company::whereHas('quotations', function ($query) {
            return $query->where('year', '=', getPpmpYear());
        })
            ->with(['quotations.items.pr_item.pr', 'quotations.items.pr_item.ppmp.item_detail.unit'])
            ->get();
        // return $quotation_summaries;
        return view('po-dashboard/quotation-summary')->with('quotation_summaries', $quotation_summaries);
    }

    public function get_company_quotations($company_id)
    {
        // $canvassed_items = CanvassAbstractItem::with('quotation_item')
        //     ->with('quotation_item.quotation', function ($query) {
        //         $query->where('')
        //     });
        try {
            $company_quotations = Company::where('id', '=', $company_id)
                ->whereHas('quotations', function ($query) {
                    $query->where('year', '=', Auth::user()->ppmp_year);
                })
                ->whereDoesntHave('canvass_abstract', function ($query) {
                    $query->where('year', '=', Auth::user()->ppmp_year);
                })
                ->with('quotations.items.ppmp.item_detail', function ($query) {
                    $query->whereNotIn('item_details.id', function ($innerQuery) {
                        $innerQuery
                            ->select('item_details.id')
                            ->from('canvass_abstract_items')
                            ->leftJoin('quotation_items as q2', 'canvass_abstract_items.quotation_items_id', '=', 'q2.id')
                            ->leftJoin('pro_pro_man_plans', 'q2.pro_pro_man_plans_id', '=', 'pro_pro_man_plans.id')
                            ->leftJoin('item_details', 'pro_pro_man_plans.item_details_id', '=', 'item_details.id');
                    });
                })
                // ->with('quotations.items', function ($query1) {
                //     $query1->whereDoesntHave('ppmp.item_detail', function ($query2) {
                //         $query2->whereNotIn('item_details.id', function ($query3) {
                //             $query3->select('item_details.id')
                //                 ->from('canvass_abstract_items')
                //                 ->leftJoin('quotation_items as q2', 'canvass_abstract_items.quotation_items_id', '=', 'q2.id')
                //                 ->leftJoin('pro_pro_man_plans', 'q2.pro_pro_man_plans_id', '=', 'pro_pro_man_plans.id')
                //                 ->leftJoin('item_details', 'pro_pro_man_plans.item_details_id', '=', 'item_details.id');
                //         });
                //     })->with('ppmp.item_detail.unit');
                // })
                ->get();

            if (count($company_quotations) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $company_quotations
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No records found for this company.'
                ], 404);
            }
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Cannot process your request.'
            ], 500);
        }
    }

    public function get_item_for_comparison($pr_id)
    {
        try {
            $items = QuotationItem::whereIn('quotations_id', function ($query) {
                $query->where('year', '=', Auth::user()->ppmp_year)->select('id')->from('quotations')->get();
            })
                ->where('purchase_request_items_id', '=', $pr_id)
                ->with(['quotation', 'quotation.company', 'pr_item.ppmp.item_detail.unit'])
                ->get();

            if (count($items) > 0) {
                return response()->json([
                    'success' => true,
                    'data' => $items
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No records found for this item.'
                ], 404);
            }
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Cannot process your request.'
            ], 500);
        }
    }

    public function rfq_index() {
        $purchase_requests = PurchaseRequest::with(['rfq'])
            ->has('rfq')
            ->where('year', getPpmpYear())
            ->get();

        return view('po-dashboard.all_rfq')
            ->with('purchase_requests', $purchase_requests);
    }

    public function rfq_add() {
        $purchase_requests = PurchaseRequest::doesntHave('rfq')
            ->where('year', Auth::user()->ppmp_year)
            ->get();
        
        $mode_of_procurements = ModeOfProcurement::all();

        return view('po-dashboard.add_rfq')
            ->with('purchase_requests', $purchase_requests)
            ->with('mode_of_procurements', $mode_of_procurements);
    }

    public function rfq_create(Request $request) {
        $request->validate([
            'purchase_requests_id' => 'required|exists:purchase_requests,id',
            'deadline_of_submission' => 'required|date|after:yesterday',
            'mode_of_procurements_id' => 'required|exists:mode_of_procurements,id',
            'approved_budget' => 'required|numeric',
            'head_procurement' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $latest_rfq = QuotationRequest::where('year', getPpmpYear())
                ->latest()
                ->first();

            $quotation_number_ctr = $latest_rfq === null ? 1 : intval(substr($latest_rfq->quotation_number, 9, 4)) + 1;

            $q_number = sprintf(
                '%s-%s-%s',
                getPpmpYear(),
                date('m'),
                str_pad($quotation_number_ctr, 4, '0', STR_PAD_LEFT),
            );

            $newRfq = new QuotationRequest();
            $newRfq->year = getPpmpYear();
            $newRfq->quotation_number = $q_number;
            $newRfq->purchase_requests_id = $request->purchase_requests_id;
            $newRfq->deadline_of_submission = $request->deadline_of_submission;
            $newRfq->mode_of_procurements_id = $request->mode_of_procurements_id;
            $newRfq->approved_budget = $request->approved_budget;
            $newRfq->head_procurement = $request->head_procurement;  
            
            $newRfq->save();
            DB::commit();
            
            return redirect()->back()->with('success', 'Request for quotation successfully saved!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong! Please contact web administrator!']);
        }
    }

    public function rfq_print($id) {
        $rfq = QuotationRequest::find($id);
        return view('po-dashboard.print_rfq')
            ->with('rfq', $rfq);
    }
}
