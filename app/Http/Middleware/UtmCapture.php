<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

/**
 * Class UtmCapture
 * @package App\Http\Middleware
 */
class UtmCapture
{
    /**
     * Handle an incoming request and capture UTM.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Collect cookies
        $cookies = [];
        foreach ($request->all() as $name => $value) {
            if (
                substr($name, 0, 4) == 'utm_'
                && (is_numeric($value) || is_string($value))
            ) {
                $cookies[$name] = cookie($name, $value, 262800, null, null, false, false); // 6 months
            }
        }

        // Default utm source is direct
        if (! isset($cookies['utm_source']) && ! \Cookie::get('utm_source', null) && ! $request->header('referer')) {
            $cookies['utm_source'] = cookie('utm_source', 'direct', 262800, null, null, false, false); // 6 months;
        }

        // Apply cookies
        $response = $next($request);
        foreach ($cookies as $name => $cookie) {
            $response->cookie($cookie);
        }

        return $response;
    }
}
