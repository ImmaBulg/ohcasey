<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdmin
{
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->superadmin) {
            return redirect()->back();
        }

        return $next($request);
    }
}
