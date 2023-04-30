<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Throwable;

class CompanyController extends Controller
{
    public function all()
    {
        $company_profiles = Company::all();
        return view('po-dashboard/company-profile-list')->with('company_profiles', $company_profiles);
    }

    public function add(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|min:3|max:255',
        //     'full_address' => 'required|min:5|max:255',
        //     'tin' => 'required|min:9|max:20',
        //     'contact_number' => 'required',
        //     'email_address' => 'required|email',
        // ], [
        //     'tin.min' => 'Please enter a valid TIN number.',
        //     'tin.max' => 'Please enter a valid TIN number.',
        // ]);

        $validator = Validator::make($request->all(), [
            'name' => ['required','min:3','max:255'],
            'full_address' => ['required','min:5', 'max:255'],
            'tin' => ['required' ,' min:9' , 'max:20', 'unique:companies,tin'],
            'contact_number' => ['required','regex:/^(02|\+63)[0-9]{7,10}$|^(\+639|09)[0-9]{9}$|^([0-9]{2,4}-)?[0-9]{6,8}$/', 'unique:companies,contact_number'],
            'email_address' => ['required', 'email', 'unique:companies,email_address'],
            'philgeps_number' => ['required']
        ], [
            'tin.min' => 'Please enter a valid TIN number.',
            'tin.max' => 'Please enter a valid TIN number.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->withErrors($errors);
        }



        DB::beginTransaction();
        try {            
            $new_company_profile = new Company();
            $new_company_profile->name = $request->name;
            $new_company_profile->full_address = $request->full_address;
            $new_company_profile->tin = $request->tin;
            $new_company_profile->contact_number = $request->contact_number;
            $new_company_profile->email_address = $request->email_address;
            $new_company_profile->philgeps_number = $request->philgeps_number;
            $new_company_profile->is_delete = 0;
            if( $request->is_in_philgeps == "on"){
                $new_company_profile->is_in_philgeps = 1;
            }else{
                $new_company_profile->is_in_philgeps = 0;
            }
            $new_company_profile->added_by = Auth::user()->id;
            $new_company_profile->save();
            DB::commit();
            return redirect()->back()->with('success', 'Successfully saved ' . $new_company_profile->name);

        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. ' . $request->name . ' is not saved. Refresh the page and try again. If the problem persists, please report to website administrator.']);
        }

        return $request->all();
    }

    public function single_api($company_id)
    {
        $company_profile = Company::where('id', '=', $company_id)->get();
        // return $company_profile;
        if (count($company_profile) === 1) {
            return response()->json([
                'success' => true,
                'data' => $company_profile
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cannot get company profile you requested.'
        ], 400);
    }

    public function update(Request $request, $company_id)
    {
        DB::beginTransaction();
        try {
            $company_profile = Company::find($company_id);
            $validator = Validator::make($request->all(), [
                'name' => ['required','min:3','max:255'],
                'full_address' => ['required','min:5', 'max:255'],
                'tin' => [
                    'required' ,
                    'min:9' , 
                    'max:20', 
                    Rule::unique('companies', 'tin')->ignore($company_profile)],
                'contact_number' => [
                    'required',
                    'regex:/^(02|\+63)[0-9]{7,10}$|^(\+639|09)[0-9]{9}$|^([0-9]{2,4}-)?[0-9]{6,8}$/',
                    Rule::unique('companies', 'contact_number')->ignore($company_profile)],
                'email_address' => [
                    'required',
                     'email',
                    Rule::unique('companies', 'email_address')->ignore($company_profile)],
                'philgeps_number' => ['required']
            ], [
                'tin.min' => 'Please enter a valid TIN number.',
                'tin.max' => 'Please enter a valid TIN number.',
            ]);
    
            if ($validator->fails()) {
                $errors = $validator->errors();
                return redirect()->back()->withErrors($errors);
            }


            $company_profile->name = $request->name;
            $company_profile->full_address = $request->full_address;
            $company_profile->tin = $request->tin;
            $company_profile->contact_number = $request->contact_number;
            $company_profile->email_address = $request->email_address;
            $company_profile->philgeps_number = $request->philgeps_number;
            $company_profile->is_in_philgeps = $request->input('is_in_philgeps') ? 1 : 0;


            $company_profile->save();
            DB::commit();
            return redirect()->back()->with('success', 'Company profile successfully updated.');
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Company profile not updated. Please try again and if the error persists, contact the website administrator.']);
        }
    }

    public function toggleDelete($company_id)
    {
        $company_profile_to_delete = Company::find($company_id);
        DB::beginTransaction();
        try {
            $company_profile_to_delete->is_delete = $company_profile_to_delete->is_delete === 1 ? 0 : 1;
            $company_profile_to_delete->save();
            DB::commit();
            return redirect()->back()->with('success', 'Updates processed.');
        } catch (Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['Something went wrong. Updates failed to process.']);
        }
    }

    public function status_change($id, $isChecked){

        DB::beginTransaction();
        try {
            $company = Company::find($id);
            $company->is_in_philgeps = $isChecked;
            $company->save();

            DB::commit();
            back()
                ->with('success', 'User status successfully updated.');
            return response()->json([
                "success" => true
            ], 200);

        } catch (Throwable $e) {
            DB::rollBack();
            back()
                ->withErrors(['Something went wrong! User changes is not saved.']);
            return response()->json([
                "success" => false
            ], 400);
        }

    }

    public function get_company_by_bac_reso($bac_reso_id)
    {
        $companies = Company::whereHas('quotations.items.pr_item.pr.abstract_of_canvass.bac_reso', function ($builder) use ($bac_reso_id) {
            $builder->where('id', $bac_reso_id);
        })
            // ->doesntHave('purchase_orders')
            // ->with(['quotations.items.bac_reso_item'])
            ->whereHas('quotations.items.bac_reso_item', function ($builder) use ($bac_reso_id) {
                $builder->where('b_a_c_resos_id', $bac_reso_id);
            })
            ->get();

        try {
            if (count($companies) <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Companies not found.'
                ], 404);
            } else {
                return response()->json([
                    'success' => true,
                    'data' => $companies
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Cannot fetch companies.'
            ], 400);
        }
    }
}
