<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ItemCategory;
use App\Models\ItemDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\LoginRequest;

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

    public function add_user(LoginRequest $request)
    {
        $newUser = new User();
        $newUser->username = "admin";
        $newUser->email = "admin@admin.com";
        $newUser->password = "admin";
        $newUser->account_type = "admin";
        $newUser->save();

        return response()->json($newUser);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect('/');
    }

    protected function authenticated(Request $request, $user, string $page)
    {
        return redirect()->intended($page);
    }
}
