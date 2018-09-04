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
use App\Models\Shop\OptionGroup;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use yii\helpers\ArrayHelper;

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
        if ($request->has('f_type')) {
            $option_group_id = OptionGroup::where(['name' => $request->input('f_type')])->first();
            $cart_set_products->join('offers', 'cart_set_product.offer_id', '=', 'offers.id')->join('products', 'offers.product_id', '=', 'products.id')->where(['products.option_group_id' => $option_group_id->id]);
        }
        if ($request->has('f_size')) {
            $cart_set_products->join('offers', 'cart_set_product.offer_id', '=', 'offers.id')->join('offer_option_value', 'offers.id', '=', 'offer_option_value.offer_id')->where(['offer_option_value' => $request->has('f_size')]);
        }
        if ($request->has('f_print_type')) {
            $cart_set_products->where(['print' => $request->input('f_print_type')]);
        }
        if ($request->has('f_count')) {
            $cart_set_products->where(['item_count' => $request->input('f_count')]);
        }
        if ($request->has('f_print_date')) {
            $cart_set_products->where(['date_send' => $request->input('f_print_date')]);
        }
        if ($request->has('f_delivery_type')) {
            $cart_set_products->join('order', 'cart.cart_order_id', '=', 'order.order_id')->where(['order.delivery_name' => $request->input('f_delivery_type')]);
        }
        if ($request->has('f_delivery_date')) {
            $orders = Order::where('order_ts', 'like', $request->has('f_delivery_date'))->get();
            $ids = [];
            foreach ($orders as $order) {
                $tmp = $order->cart->cartSetProducts()->pluck('id');
                ArrayHelper::merge($ids, $tmp);
            }
            $cart_set_products->whereIn('id', $ids);
        }
        if ($request->has('f_status')) {
            if (gettype($request->input('f_status')) === 'array') {
                $cart_set_products->whereIn('print_status_id', $request->input('f_status'));
            } else {
                $cart_set_products->where(['print_status_id' => $request->input('f_status')]);
            }
        }
        if ($request->has('f_name')) {
            $cart_set_products->join('offers', 'cart_set_product.offer_id', '=', 'offers.id')->join('products', 'offers.product_id', '=', 'products.id')->where(['products.title' => $request->input('f_name')]);
        }
        if (!$request->has('f_date')) {
            $cart_set_products->join('order', 'cart.cart_order_id', '=', 'order.order_id')->whereBetween('order.order_ts', [$startOrder, $endDate]);
        }

        return $cart_set_products;
    }
}