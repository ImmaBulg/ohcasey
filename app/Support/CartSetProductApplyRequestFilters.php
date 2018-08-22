<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 21.08.2018
 * Time: 10:43
 */
namespace App\Support;

use App\Models\Order;
use App\Models\CartSetProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CartSetProductApplyRequestFilters
{
    function applyFilter(Builder $cart_set_products, Request $request, Carbon $endDate, Carbon $startOrder = null) {
        if ($request->has('f_date')) {
            $end = Carbon::parse($request->input('f_date', 'NOW'))->endOfDay();
            $start =  $start = Carbon::parse($request->input('f_date'))->startOfDay();
            $cart_set_products->whereBetween('cart.cart_ts', [$start, $end]);
        }
        if ($request->has('f_item')) {
            $cart_set_products->where(['id' => $request->input('f_item')]);
        }
        if ($request->has('f_order')) {
            $order = Order::where(['order_id' => $request->input('f_order')])->first();
            $tmp = CartSetProduct::where(['cart_id' => $order->cart->cart_id])->get();
            $ids = [];
            foreach ($tmp as $item) {
                $ids[] = $item->id;
            }
            $cart_set_products->whereIn('id', $ids);
        }
        if ($request->has('f_status')) {
            $cart_set_products->where(['print_status_id' => $request->input('f_status')]);
        }
        if ($request->has('f_name')) {
            $cart_set_products->join('offers', 'cart_set_product.offer_id', '=', 'offers.id')->join('products', 'offers.product_id', '=', 'products.id')->where(['products.title' => $request->input('f_name')]);
        }
        if (!$request->has('f_date')) {
            $cart_set_products->whereBetween('cart.cart_ts', [$startOrder, $endDate]);
        }

        return $cart_set_products;
    }
}