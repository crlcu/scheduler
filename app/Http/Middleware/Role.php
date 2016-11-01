<?php

namespace App\Http\Middleware;

use Closure;

class Role
{
    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (! $request->user()->hasRole($role))
        {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
