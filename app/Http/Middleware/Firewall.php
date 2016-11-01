<?php

namespace App\Http\Middleware;

use Antiflood;
use Closure;

class Firewall
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Antiflood::check($request->fingerprint(), config('firewall.requests')))
        {
            Antiflood::put($request->fingerprint(), config('firewall.minutes'));
        }
        else
        {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
