<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use App\Models\Position;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('po-dashboard/user-list')->with('users', $users);
    }

    public function add()
    {
        $branches = Branch::all();
        $positions = Position::all();
        return view('po-dashboard/add-new-user')
            ->with('branches', $branches)
            ->with('positions', $positions);
    }

    public function show($user_id)
    {
        $user = User::find($user_id);
        $positions = Position::all();
        $branches = Branch::all();
        return view('po-dashboard/view-user')
            ->with('user', $user)
            ->with('positions', $positions)
            ->with('branches', $branches);
    }

    public function save_new(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users|min:3',
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'account_type' => 'required|string',
            'branches_id' => 'required|numeric|min:1',
            'positions_id' => 'required|numeric|min:1'
        ]);

        $inputs = $request->all();
        $user = Auth::user();

        if ($user->account_type !== "admin" && $user->account_type !== "PROCUREMENT_OFFICE") {
            return redirect()->route('dashboard.show');
        } else {
            DB::beginTransaction();
            try {
                $newUser = new User();
                $newUser->username = $inputs['username'];
                $newUser->password = "a";
                $newUser->email = $inputs['email'];
                $newUser->account_type = $inputs['account_type'];
                $newUser->ppmp_year = date('Y');
                $newUser->branches_id = $inputs['branches_id'];
                $newUser->is_active = 1;
                $newUser->save();

                $newUserProfile = new UserProfile();
                $newUserProfile->users_id = $newUser->id;
                $newUserProfile->first_name = $inputs['first_name'];
                $newUserProfile->last_name = $inputs['last_name'];
                $newUserProfile->positions_id = $inputs['positions_id'];
                $newUserProfile->save();

                DB::commit();
                return redirect()
                    ->back()
                    ->with('success', 'New user successfully added! Please let them know to reset the password first to setup the account!');
            } catch (Throwable $e) {
                DB::rollBack();
                return redirect()
                    ->back()
                    ->withErrors(['Something went wrong! User is not created.']);
            }
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $request->id,
            'username' => 'required|unique:users,username,' . $request->id . '|min:3',
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'account_type' => 'required|string',
            'branches_id' => 'required|numeric|min:1',
            'positions_id' => 'required|numeric|min:1'
        ]);

        $inputs = $request->all();
        $user = Auth::user();

        if ($user->account_type !== "admin" && $user->account_type !== "PROCUREMENT_OFFICE") {
            return redirect()->route('dashboard.show');
        } else {
            DB::beginTransaction();
            try {
                $newUser = User::find($inputs["id"]);
                $newUser->username = $inputs['username'];
                $newUser->password = "a";
                $newUser->email = $inputs['email'];
                $newUser->account_type = $inputs['account_type'];
                $newUser->ppmp_year = date('Y');
                $newUser->branches_id = $inputs['branches_id'];
                $newUser->is_active = 1;
                $newUser->save();

                $newUserProfile = UserProfile::find($inputs["profile_id"]);
                $newUserProfile->users_id = $newUser->id;
                $newUserProfile->first_name = $inputs['first_name'];
                $newUserProfile->last_name = $inputs['last_name'];
                $newUserProfile->positions_id = $inputs['positions_id'];
                $newUserProfile->save();

                DB::commit();
                back()
                    ->with('success', 'User details successfully updated.');
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
    }
    public function status_manage($id, $st){
            $users = User::find($id);
            $users->is_active= $st;
            $users->save();
    }
    
}
