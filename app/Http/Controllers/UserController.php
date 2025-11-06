<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Position;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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

        if ($user->account_type === 'admin') {
            return redirect()->back()->withErrors(['Admin account cannot be edited.']);
        }
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
            'positions_id' => 'required|numeric|min:1',
        ]);

        $inputs = $request->all();
        $user = Auth::user();

        if ($user->account_type !== 'admin' && $user->account_type !== 'PROCUREMENT_OFFICE' && $user->account_type !== 'PROCUREMENT_HEAD') {
            return redirect()->route('dashboard.show');
        } else {
            DB::beginTransaction();
            try {
                $newUser = new User();
                $newUser->username = $inputs['username'];
                $newUser->password = 'a';
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

                $token = Str::random(64);
                DB::table('password_resets')->insert([
                    'email' => $inputs['email'],
                    'token' => $token,
                    'created_at' => Carbon::now(),
                ]);

                Mail::send('email/new-account-notif', ['token' => $token], function ($message) use ($inputs) {
                    $message->to($inputs['email']);
                    $message->subject('[BulSU e-Procurement] Welcome to BulSU e-Procurement System! Please activate your account!');
                });

                DB::commit();

                return redirect()
                    ->back()
                    ->with('success', 'New user successfully added! Please let them know to reset the password first to setup the account password!');
            } catch (Throwable $e) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->withErrors(['Something went wrong! New user is not saved.']);
            }
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,'.$request->id,
            'username' => 'required|unique:users,username,'.$request->id.'|min:3',
            'first_name' => 'required|min:1',
            'last_name' => 'required|min:1',
            'account_type' => 'required|string',
            'branches_id' => 'required|numeric|min:1',
            'positions_id' => 'required|numeric|min:1',
        ]);

        $inputs = $request->all();
        $user = Auth::user();

        if ($user->account_type !== 'admin' && $user->account_type !== 'PROCUREMENT_OFFICE') {
            return redirect()->route('dashboard.show')->withErrors(['You are not allowed to visit this page.']);
        } else {
            DB::beginTransaction();
            try {
                $newUser = User::find($inputs['id']);
                $newUser->username = $inputs['username'];
                $newUser->email = $inputs['email'];
                $newUser->account_type = $inputs['account_type'];
                $newUser->branches_id = $inputs['branches_id'];
                $newUser->save();

                $newUserProfile = UserProfile::find($newUser->profile->id);
                $newUserProfile->users_id = $newUser->id;
                $newUserProfile->first_name = $inputs['first_name'];
                $newUserProfile->last_name = $inputs['last_name'];
                $newUserProfile->positions_id = $inputs['positions_id'];
                $newUserProfile->save();

                DB::commit();

                return redirect()
                    ->back()
                    ->with('success', 'User details successfully updated.');
            } catch (Throwable $e) {
                DB::rollBack();

                return redirect()
                    ->back()
                    ->withErrors(['Something went wrong! User changes is not saved.']);
            }
        }
    }

    public function status_manage($id, $st)
    {
        DB::beginTransaction();
        try {
            $users = User::find($id);
            $users->is_active = $st;
            $users->save();

            DB::commit();
            back()
                ->with('success', 'User status successfully updated.');

            return response()->json([
                'success' => true,
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            back()
                ->withErrors(['Something went wrong! User changes is not saved.']);

            return response()->json([
                'success' => false,
            ], 400);
        }
    }

    public function account_settings()
    {
        $account_details = User::where('id', '=', Auth::user()->id)->with(['profile', 'profile.position'])->get();

        return view('global/account-settings')->with('account_details', $account_details[0]);
    }

    public function change_user_details(Request $request)
    {
        $request->validate([
            'username' => 'unique:users,username,'.Auth::user()->id,
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'email|required|min:3',
        ]);
        DB::beginTransaction();
        try {
            UserProfile::where('users_id', '=', Auth::user()->id)->update(['first_name' => $request->first_name, 'last_name' => $request->last_name]);
            User::where('id', '=', Auth::user()->id)->update(['email' => $request->email, 'username' => $request->username]);
            DB::commit();

            return redirect()->back()->with('success', 'Account details updated.');
        } catch (Throwable $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['Account details changes not saved.']);
        }
    }

    public function delete_user($id)
    {
        try {
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['Account failed to delete.']);
        }
    }
}
