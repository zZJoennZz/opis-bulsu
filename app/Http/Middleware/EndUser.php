<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndUser
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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->account_type === "BUDGET_OFFICE") {
            return redirect()->route('bo-dashboard.show');
        }

        if (Auth::user()->account_type === "PROCUREMENT_OFFICE") {
            return redirect()->route('po-dashboard.show');
        }

        if (Auth::user()->account_type === "admin" || Auth::user()->account_type === "END_USER") {
            return $next($request);
        }

        abort(403);
    }
}
