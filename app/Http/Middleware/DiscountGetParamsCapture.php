<?php

namespace App\Http\Middleware;

use App\Ohcasey\Ohcasey;
use Carbon\Carbon;
use Closure;

class DiscountGetParamsCapture
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
        $checkGetHttpParams = [
            'sp' => 'small price',
            'up' => 'usually price',
            'hp' => 'high price',
        ];

        $params = $request->all();

        foreach ($checkGetHttpParams as $paramName => $itemPriceName) {
            if (isset($params[$paramName])) {
                /** @var Ohcasey $oh */
                $oh = app(Ohcasey::class);

                $oh->setCurrentGroup($itemPriceName, Carbon::now()->addDay()->endOfDay());
                return $next($request);
            }
        }

        return $next($request);
    }
}
