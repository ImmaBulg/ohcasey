<?php

namespace App\Http\Middleware;

use App\Ohcasey\Cart as CartHelper;
use Closure;

class NotEmptyCart
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
        /** @var CartHelper $cartHelper */
        $cartHelper = app(CartHelper::class);

        if (!$cartHelper->exists() || $cartHelper->get()->summary->cnt <= 0) {
            return redirect()->route('shop.cart.empty_cart_page');
        }

        return $next($request);
    }
}
