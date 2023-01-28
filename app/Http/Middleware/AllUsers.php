<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class AllUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (intval(getSettingValue('maintenance_mode')) === 1 && Auth::user()->account_type !== "PROCUREMENT_OFFICE" && Auth::user()->account_type !== "admin") {
            Session::flush();
            Auth::logout();
            return redirect()->route('login')->withErrors(['System is currently in maintenance. Please try again later.']);
        }

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->is_active) {
            Session::flush();
            Auth::logout();
            return redirect('/')->withErrors(['Your account is not active. Please contact procurement office or the administrator.']);
        }

        return $next($request);
    }
}
