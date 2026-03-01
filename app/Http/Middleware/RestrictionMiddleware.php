<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictionMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();

            // If the user is Blocked or Banned
            if ($user->isBlocked() || $user->isBanned()) {

                // Allow access to the restricted page and logout route to prevent redirect loop
                if (!$request->is('account/restricted') && !$request->is('logout')) {
                    return redirect()->route('account.restricted');
                }
            }
        }

        return $next($request);
    }
}