<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Storage;

class OrderController extends Controller
{
    /**
     * @param Order $order
     * @param $hash
     * @param $imgPath
     * @return mixed
     */
    public function showImage(Order $order, $hash, $imgPath)
    {
        if ($order->order_hash != $hash) {
            throw (new ModelNotFoundException())->setModel($order);
        }
        $date = new Carbon($order->order_ts, 'Europe/Moscow');
        $file = Storage::get('orders/'.$date->format('Y/m/d').'/'.$order->order_id.'/img/'.$imgPath);

        return response($file, 200)->header('Content-Type', 'image/png');
    }
}