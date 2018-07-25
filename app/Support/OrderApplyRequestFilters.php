<?php

namespace App\Support;

use App\Models\CartSetProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OrderApplyRequestFilters
{
    /**
     * Ограничить выборку заказов по данным из формы.
     *
     * @param Builder $orders
     * @param Request $request
     * @param Carbon $startOrder
     * @param Carbon $endDate
     * @return Builder
     */
    public function applyFilter(Builder $orders, Request $request, Carbon $endDate, Carbon $startOrder = null)
    {
        if ($request->has('f_cartSetProductId')) {
            $cartSetProduct = CartSetProduct::where('id', '=', $request->input('f_cartSetProductId'))->get();
            if ($cartSetProduct[0]->cart->order)
                $orders->where('order_id', '=', $cartSetProduct[0]->cart->order->order_id);
            else
                $orders->where('order_id', '=', -1);
        }
        if ($request->has('f_id')) {
            $orders->where('order_id', '=', (int)$request->get('f_id'));
        }
        if ($request->has('f_status')) {
            $orders->whereRaw('order_status_id in (select status_id from order_status where status_name like ?)', ['%'.$request->get('f_status').'%']);
        }
        if ($request->has('f_client')) {
            $orders->where('client_name', 'ilike', '%'.$request->get('f_client').'%');
        }
        if ($request->has('f_phone')) {
            $orders->where('client_phone', 'ilike', '%'.$request->get('f_phone').'%');
        }
        if ($request->has('f_email')) {
            $orders->where('client_email', 'ilike', '%'.$request->get('f_email').'%');
        }
        if ($request->has('f_utm')) {
            $orders->where('utm', 'ilike', '%'.$request->get('f_utm').'%');
        }
        if ($startOrder) {
            $orders->where('order_ts', '>=', $startOrder->format('Y-m-d'));
        }
        if ($endDate) {
            $orders->where('order_ts', '<', $endDate->format('Y-m-d'));
        }
        if ($request->get('with_trashed', false)) {
            $orders->withTrashed();
        }
        return $orders;
    }
}