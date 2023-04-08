<?php

namespace App\Http\Controllers;

use App\Models\AbstractOfCanvass;
use App\Models\Company;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbstractOfCanvassController extends Controller
{
    public function all()
    {
        $aocs = AbstractOfCanvass::with(['pr'])->whereHas('pr', function ($builder) {
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
        $aoc = AbstractOfCanvass::where('id', '=', $id)
            ->with(['pr.pr_items.ppmp.item_detail.unit', 'pr.pr_items.ppmp.milestones'])
            ->where('is_delete', '=', 0)
            ->get();

        if ($aoc[0]->type === "BY_LOT") {
            $companies = Company::whereHas('quotations.items.pr_item.pr', function ($builder) use ($aoc) {
                $builder->where('id', '=', $aoc[0]->pr->id);
            })
                ->has('quotations.items', '=', count($aoc[0]->pr->pr_items))
                ->with(['quotations.items.pr_item.pr', 'quotations.items.pr_item.ppmp.item_detail.unit'])
                ->get();
        } else {
            $companies = Company::whereHas('quotations.items.pr_item.pr', function ($builder) use ($aoc) {
                $builder->where('id', '=', $aoc[0]->pr->id);
            })
                ->with(['quotations.items.pr_item.pr', 'quotations.items.pr_item.ppmp.item_detail.unit'])
                ->get();
        }

        // return $companies;
        return view('po-dashboard/view-abstract-of-canvass')
            ->with('aoc', $aoc)
            ->with('companies', $companies);
    }

    public function print($id)
    {
        $aoc = AbstractOfCanvass::where('id', '=', $id)
            ->with(['pr.pr_items.ppmp.item_detail.unit', 'pr.pr_items.ppmp.milestones'])
            ->where('is_delete', '=', 0)
            ->get();

        if ($aoc[0]->type === "BY_LOT") {
            $companies = Company::whereHas('quotations.items.pr_item.pr', function ($builder) use ($aoc) {
                $builder->where('id', '=', $aoc[0]->pr->id);
            })
                ->has('quotations.items', '=', count($aoc[0]->pr->pr_items))
                ->with(['quotations.items.pr_item.pr', 'quotations.items.pr_item.ppmp.item_detail.unit'])
                ->get();
        } else {
            $companies = Company::whereHas('quotations.items.pr_item.pr', function ($builder) use ($aoc) {
                $builder->where('id', '=', $aoc[0]->pr->id);
            })
                ->with(['quotations.items.pr_item.pr', 'quotations.items.pr_item.ppmp.item_detail.unit'])
                ->get();
        }

        // return $companies;
        return view('po-dashboard/print-abstract-of-canvass')
            ->with('aoc', $aoc)
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
            $new_aoc->end_user = $request->end_user;
            $new_aoc->president = $request->president;
            $new_aoc->procurement_office_rep = $request->procurement_office_rep;
            $new_aoc->added_by = Auth::user()->id;
            $new_aoc->save();
            DB::commit();
            return redirect()->route('aoc.all')->with('success', 'Abstract of Canvass saved!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong! Cannot save abstract of canvass.']);
        }
    }
}
