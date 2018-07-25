<div class="panel-body panel-body-delivery js-delivery-info">
    <table>
        @if($order->delivery_name)
            <tr>
                <td style="padding:5px" width="50%">Доставка</td>
                <td style="padding:5px">{{ $order->delivery->delivery_caption }}</td>
            </tr>
        @endif
        @if($order->country_iso)
            <tr>
                <td style="padding:5px" width="50%">Страна</td>
                <td style="padding:5px">{{ $order->country->country_name_ru }}</td>
            </tr>
        @endif
        @if($order->city)
            <tr>
                <td style="padding:5px" width="50%">Город</td>
                <td style="padding:5px">{{ $order->city->city_name_full }}</td>
            </tr>
        @endif
        @if($order->delivery_name == 'courier' || $order->delivery_name == 'pickpoint')
            @if($order->delivery_name == 'pickpoint')
                <tr>
                    <td style="padding:5px" width="50%">Пункт выдачи</td>
                    <td style="padding:5px">{{ $order->deliveryCdek->cdek_pvz.' | '.$order->deliveryCdek->cdek_pvz_name }}</td>
                </tr>
                <tr>
                    <td style="padding:5px" width="50%">Адрес</td>
                    <td style="padding:5px">{{ $order->deliveryCdek->cdek_pvz_address }}</td>
                </tr>
                @if(!isset($short))
                    <tr>
                        <td style="padding:5px" width="50%">Режим работы</td>
                        <td style="padding:5px">{{ $order->deliveryCdek->cdek_pvz_worktime }}</td>
                    </tr>
                @endif
            @endif
        @elseif($order->delivery_name == 'post')
            <tr>
                <td style="padding:5px" width="50%">Индекс</td>
                <td style="padding:5px">{{ $order->deliveryRussianPost->post_code }}</td>
            </tr>
        @endif
        @if(!empty($order->delivery_address))
            <tr>
                <td style="padding:5px" width="50%">Адрес</td>
                <td style="padding:5px">{{ $order->delivery_address }}</td>
            </tr>
        @endif
        @if(!empty($order->delivery_amount))
            <tr>
                <td style="padding:5px" width="50%">Стоимость доставки</td>
                <td style="padding:5px">{{ $order->delivery_amount }} <span class="icon-rouble"></span></td>
            </tr>
        @endif
        @if (!empty($order->delivery_time_from))
            <tr>
                <td style="padding:5px" width="50%">Дата доставки</td>
                <td style="padding:5px">{{ $order->delivery_time_from }} - {{ $order->delivery_time_to }}</td>
            </tr>
        @endif
    </table>
</div>