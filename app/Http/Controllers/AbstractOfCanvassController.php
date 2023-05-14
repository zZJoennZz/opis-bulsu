<?php

namespace App\Http\Controllers;

use App\Models\AbstractOfCanvass;
use App\Models\BACReso;
use App\Models\BACResoItem;
use App\Models\Company;
use App\Models\PurchaseRequest;
use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbstractOfCanvassController extends Controller
{
    public function all()
    {
        $aocs = AbstractOfCanvass::with(['pr', 'bac_reso'])->whereHas('pr', function ($builder) {
            $builder->where('year', '=', Auth::user()->ppmp_year)->whereHas('pr_items', function ($builder1) {
                $builder1->has('quotations');
            });
        })
            ->where('is_delete', '=', '0')
            ->get();

        // return $aocs;
        return view('po-dashboard/view-abstract-of-canvasses')
            ->with('aocs', $aocs);
    }

    public function add()
    {
        $pr_without_abstract = PurchaseRequest::doesntHave('abstract_of_canvass')
            ->has('pr_items.quotations')
            ->where('year', '=', Auth::user()->ppmp_year)
            ->get();
        return view('po-dashboard/add-abstract-of-canvass')
            ->with('pr_without_abstract', $pr_without_abstract);
    }

    public function single($id)
    {
        $bac_reso = BACReso::with(['bac_reso_items.quotation.pr_item', 'bac_reso_items.quotation.pr_item.ppmp.item_detail.unit', 'abstract_of_canvass.pr.pr_items.quotations.bac_reso_item'])
            ->where('id', '=', $id)
            ->where('is_delete', '=', 0)
            ->first();

        // return $bac_reso;
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
        $view_bac_view = 'po-dashboard/view-abstract-of-canvass';
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

    public function print($id)
    {
        $bac_reso = BACReso::with(['bac_reso_items.quotation.pr_item', 'bac_reso_items.quotation.pr_item.ppmp.item_detail.unit', 'abstract_of_canvass.pr.pr_items.quotations.bac_reso_item'])
            ->where('id', '=', $id)
            ->where('is_delete', '=', 0)
            ->first();

        // if ($bac_reso->abstract_of_canvass->type === "BY_LOT") {
        //     return redirect()->route('bac-reso.all')->withErrors(['Invalid BAC reso.']);
        // }

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

        return view('po-dashboard/print-abstract-of-canvass')
            ->with('bac_reso', $bac_reso)
            ->with('purchase_request_items', $purchase_request_items)
            ->with('quotations', $quotations)
            ->with('total_count_products', $total_products)
            ->with('companies', $companies);
    }

    public function save(Request $request)
    {
        // $request->validate([
        //     'purchase_requests_id' => 'exists:App\Models\PurchaseRequest,id',
        //     'purpose' => '';
        // ]);

        DB::beginTransaction();
        try {
            $pr = PurchaseRequest::with(['requester.profile'])->where('id', $request->purchase_requests_id)->first();
            $new_aoc = new AbstractOfCanvass();
            $new_aoc->year = getPpmpYear();
            $new_aoc->purchase_requests_id = $request->purchase_requests_id;
            $new_aoc->abc = $request->abc;
            $new_aoc->type = $request->type;
            $new_aoc->bac_chairman = $request->bac_chairman;
            $new_aoc->vice_chairman = $request->vice_chairman;
            $new_aoc->member_1 = $request->member_1;
            $new_aoc->member_2 = $request->member_2;
            $new_aoc->member_3 = $request->member_3;
            $new_aoc->member_4 = $request->member_4;
            $new_aoc->technical_resource_person = $request->technical_resource_person;
            $new_aoc->end_user = $pr->requester->profile->first_name . ' ' . $pr->requester->profile->last_name;
            $new_aoc->president = $request->president;
            $new_aoc->procurement_office_rep = $request->procurement_office_rep;
            $new_aoc->added_by = Auth::user()->id;
            $new_aoc->save();

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

            $new_bac = new BACReso();
            $new_bac->b_a_c_reso_number = $bac_reso_number;
            $new_bac->abstract_of_canvasses_id = $new_aoc->id;
            $new_bac->is_draft = 1;
            $new_bac->added_by = Auth::user()->id;
            $new_bac->save();

            DB::commit();
            return redirect()->route('aoc.single', ['id' => $new_aoc->id])->with('success', 'Abstract of Canvass saved!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong! Cannot save abstract of canvass.']);
        }
    }

    public function complete_aoc(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $aoc = AbstractOfCanvass::find($id);

            if ($aoc === null || $aoc->year !== getPpmpYear()) {
                return redirect()->back()->withErrors(['Invalid action. Please do it properly.']);
            }

            $aoc->is_draft = 0;
            $aoc->abc = $request->abc;
            $aoc->bac_chairman = $request->bac_chairman;
            $aoc->vice_chairman = $request->vice_chairman;
            $aoc->member_1 = $request->member_1;
            $aoc->member_2 = $request->member_2;
            $aoc->member_3 = $request->member_3;
            $aoc->member_4 = $request->member_4;

            $aoc->save();

            DB::commit();
            return redirect()->route('aoc.all')->with('success', 'Abstract of Canvass completed!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Cannot process action. Please try again.']);
        }
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $aoc = AbstractOfCanvass::where('id', $id)
                ->doesntHave('bac_reso.purchase_order')
                ->first();

            if ($aoc === null) {
                return redirect()
                    ->back()
                    ->withErrors(['The abstract of canvass cannot be deleted as it is associated with a purchase order, inspection and acceptance, and ALOBS. Please ensure that these items are properly deleted before attempting to delete the abstract of canvass.']);
            }

            BACResoItem::where('b_a_c_resos_id', $aoc->bac_reso->id)->delete();
            BACReso::where('id', $aoc->bac_reso->id)->delete();
            AbstractOfCanvass::find($id)->delete();

            DB::commit();
            return redirect()->route('aoc.all')->with('success', 'Abstract of canvass successfully deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Cannot process deletion. Record not deleted.']);
        }
    }
}
