<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Password;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Passwords;


class AuthController extends Controller
{
    public function show()
    {
        return view('home');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->getCredentials();

        if (!Auth::validate($credentials)) {
            return redirect()->to('/')->withErrors(trans('auth.failed'));
        }
        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        Auth::login($user);

        return $this->authenticated($request, $user, "dashboard");
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }

    public function show_forgot_password_form()
    {
        return view('auth/forgot-password');
    }

    public function submit_forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email/forgot-password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('[BulSU e-Procurement] Reset Password');
        });

        return redirect()->back()->with('message', 'We have sent you your reset password link. Please check your email inbox or spam folder!');
    }

    public function show_reset_password_form($token)
    {
        return view('auth/forgot-password-link', ['token' => $token]);
    }

    public function submit_reset_password_form(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => [
                'required|string|min:6|confirmed',
                Passwords::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised(),
            ],

            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }

        User::where('email', $request->email)
            ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email' => $request->email])->delete();

        return redirect('/')->with('message', 'Your password has been updated!');
    }

    protected function authenticated(Request $request, $user, string $page)
    {
        return redirect()->intended($page);
    }
}
