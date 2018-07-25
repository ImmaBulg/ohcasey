<?php

namespace App\Http\Middleware;

use Closure;
use yii\helpers\VarDumper;

class UserRole
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
        if ($request->user()->role == 'manager') {
            return redirect()->back();
        }

        return $next($request);
    }
}
