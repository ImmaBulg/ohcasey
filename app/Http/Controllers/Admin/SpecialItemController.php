<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecialItemStoreRequest;
use App\Models\Order;
use App\Models\SpecialOrderItem;

/**
 * Class PaymentController
 * @package App\Http\Controllers\Admin
 */
class SpecialItemController extends Controller
{

    /**
     * @param Order $order
     * @param SpecialItemStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Order $order, SpecialItemStoreRequest $request)
    {
        SpecialOrderItem::create($request->all() + ['order_id' => $order->order_id]);

        return redirect()->to(\URL::previous() . '#specialItems')->with('success', ['Сопутствующий товар добавлен.']);
    }

    /**
     * @param Order $order
     * @param SpecialOrderItem $item
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function delete(Order $order, SpecialOrderItem $item)
    {
        $item->delete();
        return redirect()->to(\URL::previous() . '#specialItems')->with('success', ['Сопутствующий товар удалён.']);
    }
}