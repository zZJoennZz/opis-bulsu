<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class BranchController extends Controller
{
    public function all()
    {
        $allBranch = Branch::all();
        return view('po-dashboard/branch-list')
            ->with('branches', $allBranch);
    }

    public function add(Request $request)
    {
        $newBranch = new Branch();
        DB::beginTransaction();
        try {
            $newBranch->branch_name = $request->branch_name;
            $newBranch->type = $request->type;
            $newBranch->address = $request->address;
            $newBranch->email_address = $request->email_address;
            $newBranch->contact_number = $request->contact_number;
            $newBranch->added_by = Auth::user()->id;
            $newBranch->save();
            $Branch = Branch::all();

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Branch successfully added!')
                ->with('branches', $Branch);
        } catch (Throwable $e) {
            DB::rollBack();
            $Branch = Branch::all();
            return redirect()->back()
                ->withErrors(['Something went wrong! Branch not added.'])
                ->with('branches', $Branch);
        }
    }

    public function get($branch_id)
    {
        $branch = Branch::find($branch_id);

        return response()->json($branch, 200);
    }

    public function update(Request $request, $branch_id)
    {
        $getBranch = Branch::find($branch_id);
        DB::beginTransaction();

        // try {
        $getBranch->branch_name = $request->branch_name;
        $getBranch->type = $request->type;
        $getBranch->address = $request->address;
        $getBranch->email_address = $request->email_address;
        $getBranch->contact_number = $request->contact_number;
        $getBranch->save();

        DB::commit();

        session(['success' => 'Branch successfully updated!']);
        return response()->json([
            'success' => true,
        ], 200);
        // } catch (Throwable $e) {
        //     DB::rollBack();
        //     return response()->json([
        //         'success' => false,
        //     ], 400);
        // }
    }

    public function delete_single($branch_id)
    {
        $getBranch = Branch::find($branch_id);
        DB::beginTransaction();
        try {
            $getBranch->is_delete = 1;
            $getBranch->save();
            DB::commit();
            redirect()->back()->with('success', 'Branch successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Branch is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }

    public function delete_batch(Request $request)
    {
        DB::beginTransaction();
        try {
            Branch::whereIn('id', $request->id)->update(["is_delete" => 1]);
            DB::commit();
            redirect()->back()->with('success', 'Branch successfully deleted!');
            return response()->json([
                "success" => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            redirect()->back()->withErrors("Something went wrong. Branch is not deleted. Please contact website administrator.");
            return response()->json([
                "success" => false,
            ], 400);
        }
    }
}
