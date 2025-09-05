<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorizedAdmin
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
        if (!$this->isAdmin()) {
            abort(Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }

    protected function isAdmin()
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        return in_array($user->is_admin, ['1', '3']);
    }
}
