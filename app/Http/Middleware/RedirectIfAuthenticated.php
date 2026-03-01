<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {

            // Redirect users based on role
            if (auth()->user()->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            if (auth()->user()->role === 'trader') {
                return redirect('/trader/dashboard');
            }

            if (auth()->user()->role === 'officer') {
                return redirect('/officer/dashboard');
            }
        }

        // Guests can continue (e.g., welcome page or login/register)
        return $next($request);
    }
}