<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 16.08.2018
 * Time: 12:36
 */

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\CartSetProduct;
use App\Models\Delivery;
use App\Models\Shop\OptionValue;
use DateTime;
use Illuminate\Http\Request;
use App\Support\CartSetProductApplyRequestFilters;
use Carbon\Carbon;


class PrintsController extends Controller
{
    public function index(Request $request, CartSetProductApplyRequestFilters $processRequestFilterData) {

        $result = [];
        $end = Carbon::parse($request->input('f_date_end', 'NOW'))->endOfDay();
        $start =  $start = Carbon::parse($request->input('f_date_start'))->startOfDay();
        $cart_set_products = CartSetProduct::join('cart', 'cart_set_product.cart_id', '=', 'cart.cart_id')
            ->whereNotNull('cart.cart_order_id')
            ->orderBy('cart_set_product.created_at', 'desc');
        $cart_set_products = $processRequestFilterData->applyFilter($cart_set_products, $request, $end, $start);

        foreach ($cart_set_products->get() as $item) {
            if ($item->cart->order) {
                if ($item->offer->product)
                if ($item->offer->product->option_group_id === 9 || $item->offer->product->option_group_id === 8 || $item->offer->product->option_group_id === 7) {
                    $order_time = new DateTime($item->cart->order->order_ts);
                    $equal_time = clone $order_time;
                    $equal_time = $equal_time->modify('18:00');
                    $tmp = [
                        'order_time' => $order_time->format('d-m-Y H:i:s'),
                        'product_id' => $item->id,
                        'order_id' => $item->cart->order->order_id,
                        'product_type' => $item->offer->product->firstCategory()->name,
                        'background_name' => $item->offer->product->title,
                        'cutting_name' => !$item->offer->optionValues()->select('title')->where(['option_id' => 10])->first() ?: $item->offer->optionValues()->select('title')->where(['option_id' => 10])->first()->title,
                        'print_size' => OptionValue::where(['id' => $item->size])->first() ? OptionValue::where(['id' => $item->size])->first()->title : "Не задан",
                        'print_type' => OptionValue::where(['id' => $item->print])->first() ? OptionValue::where(['id' => $item->print])->first()->title : "Не задан",
                        'product_count' => $item->item_count,
                        'print_status' => OptionValue::where(['id' => $item->print_status_id])->first() ? OptionValue::where(['id' => $item->print_status_id])->first()->title : "Не задан",
                        'print_date' => $order_time >= $equal_time ? $equal_time->modify('+1 day')->format('d-m-Y') : $equal_time->format('d-m-Y'),
                        'delivery_name' =>  Delivery::where(['delivery_name' => $item->cart->order->delivery_name])->first() ? Delivery::where(['delivery_name' => $item->cart->order->delivery_name])->first()->delivery_caption : 'Не задан',
                        'delivery_date' => explode(' ', $item->cart->order->delivery_time_from)[0],
                    ];
                    $result[] = $tmp;
                }
            }
        }

        $print_status = OptionValue::where(['option_id' => 12])->select('id', 'title')->get();

        return view('admin.prints.list', ['rows' => $result, 'print_status' => $print_status]);
    }

    public function updatePrintStatus(Request $request) {
        $id = $request->input('id');
        $print_status = $request->input('print_status');

        $item = CartSetProduct::where(['id' => $id])->first();
        $item->print_status_id = $print_status;
        if ($item->save())
            return ['answer' => 'success'];
        else
            return ['answer' => 'error'];
    }
}