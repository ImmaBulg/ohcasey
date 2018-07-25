<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStatusStoreRequest;
use App\Models\OrderStatus;

/**
 * Class OrderStatusController
 * @package App\Http\Controllers
 */
class OrderStatusController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.order_status.list')->with([
            'orderStatuses' => OrderStatus::withTrashed()->orderBy('sort', 'DESC')->get(),
        ]);
    }

    /**
     * @param OrderStatus|null $status
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(OrderStatus $status = null)
    {
        $status = $status ?: new OrderStatus;

        return view('admin.order_status.form')->with([
           'orderStatus' => $status,
        ]);
    }

    /**
     * @param OrderStatusStoreRequest $request
     * @param OrderStatus|null $orderStatus
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OrderStatusStoreRequest $request, OrderStatus $orderStatus = null)
    {
        /** @var OrderStatus $orderStatus */
        $orderStatus = with($orderStatus ?: new OrderStatus())->fill($request->all());
        $orderStatus->save();

        $delete = $request->get('delete', false);

        if (!$delete && $orderStatus->deleted_at) {
            $orderStatus->restore();
        } else if ($delete && !$orderStatus->deleted_at) {
            $orderStatus->delete();
        }

        return redirect()
            ->route('admin.order_statuses.index')
            ->with('success', [
                'Информация успешно обновлена'
            ]);
    }
}