<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = null)
    {
        if (! $request->user()->hasRole($role))
        {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
