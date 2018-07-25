<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12.07.2018
 * Time: 10:05
 */

namespace App\Http\Controllers;


use App\Models\Background;
use App\Models\Order;
use App\Models\OrderStatus;
use Carbon\Carbon;

class CsvController extends Controller
{
    public function getCsv($startDate, $endDate)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);
        $orders = Order::whereBetween('order_ts', [$startDate, $endDate])->get();
        $result = [];
        /**/
        $result[0] = [
            'ID заказа',
            'Дата заказа',
            'Статус заказа',
            'Название футболки',
            'Код футболки',
            'Background ID',
            'Background name',
            'Цена',
        ];
        $i = 1;
        foreach ($orders as $order)
        {
            foreach($order->cart->cartSetProducts as $cartSetProduct)
            {
                $product = $cartSetProduct->offer->product;
                if ($product->option_group_id == 9)
                {
                    $result[$i] = [
                        $order->order_id,
                        $order->order_ts,
                        OrderStatus::where(['status_id' => $order->order_status_id])->pluck('status_name')[0],
                        $product->name,
                        $product->code,
                        $product->background_id,
                        ($product->background_id) ? Background::where(['id' => $product['background_id']])->pluck('name')[0] : 'Не задан',
                        $product->price,
                    ];
                }
            }

            $i++;
        }

        $FH = fopen('file.csv', 'w');
        foreach ($result as $row) {
            fputcsv($FH, $row);
        }
        fclose($FH);

        return 'Complete';
    }
}