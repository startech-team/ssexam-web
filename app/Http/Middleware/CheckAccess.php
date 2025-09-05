<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAccess
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
        // Example: Check if user is admin
        if (auth()->user()->is_admin != 1) {
            // Redirect or abort if user does not have access
            return abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
