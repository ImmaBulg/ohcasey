<?php
use App\Models\OrderStatus;
$statuses = OrderStatus::orderBy('sort', 'DESC')->get();

$destinationOrder = session('destination_order_implode', null);
?>
@if ($destinationOrder)
    <a class="btn btn-large btn-primary" data-toggle="confirmation"
       data-title="Вы уверены?" href="{{ route('admin.order.cancel_swallow') }}">Прекратить процесс объединения</a>
@endif
<div class="table-responsive">
    <table class="table table-striped table-hover table-condensed">
        <thead>
        <tr>
            <th class="text-right" width="100">#</th>
            <th class="text-right" width="100">ID товара в заказе</th>
            <th>Дата</th>
            <th width="200">Статус</th>
            <th style="width:200px">Статус оплаты</th>
            <th>Клиент</th>
            <th>Город</th>
            <th>Телефон</th>
            <th>E-mail</th>
            {{--<th style="max-width:200px;">UTM</th>--}}
            <th class="text-right" width="70">Чехлов</th>
            <th class="text-right" width="70">Товаров</th>
            <th class="text-right" width="100">Сумма, <span class="fa fa-rub"></span></th>
            <th class="text-center"><span class="fa fa-info-circle"></span></th>
            {{--<th class="text-center"><span class="fa fa-user"></span></th>--}}
            <th class="text-center"><span class="fa fa-print"></span></th>
            <th class="text-center"><span class="fa fa-cogs"></span></th>
            <th class="text-center"><span class="fa fa-envelope"></span></th>
            <th class="text-center"><span class="fa fa-trash"></span></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><input autocomplete="off" name="f_id" value="{{ request('f_id', '') }}" type="text" class="form-control text-right" placeholder="#"></td>
            <td><input autocomplete="off" name="f_cartSetProductId" value="{{ request('f_cartSetProductId', '') }}" type="text" class="form-control" placeholder="ID продукта в заказе"></td>
            <td></td>
            <td><input autocomplete="off" name="f_status" value="{{ request('f_status', '') }}" type="text" class="form-control" placeholder="Статус"></td>
            <td style="width:200px"></td>
            <td><input autocomplete="off" name="f_client" value="{{ request('f_client', '') }}" type="text" class="form-control" placeholder="Клиент"></td>
            <td></td>            
			<td><input autocomplete="off" name="f_phone" value="{{ request('f_phone', '') }}" type="text" class="form-control" placeholder="Телефон"></td>
            <td><input autocomplete="off" name="f_email" value="{{ request('f_email', '') }}" type="text" class="form-control" placeholder="Email"></td>
            {{--<td><input autocomplete="off" name="f_utm" value="{{ request('f_utm', '') }}" type="text" class="form-control" placeholder="UTM"></td>--}}
            <?php
				$caseys_all = 0;
				$product_all = 0;
				$sum_all = 0;
				foreach($orders as $order){
					if($order->cart) $caseys_all += $order->cart->cartSetCase->count();
					if($order->cart) $product_all += $order->cart->cartSetProducts->count();
					$sum_all += $order->getTotalSum();
				}
			?>
			
			<td style="text-align: right;">{{ $counters['allCasesSum'] }}</td>
            <td style="text-align: right;">{{ $productsSum }}</td>
            <td style="text-align: right;">{{ $ordersSum }}({{$ordersSumStatus}})</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        </tbody>
        <tbody>
        @forelse($orders as $order)
            <tr class="order_row" data-order-id="{{ $order->order_id }}">
                <td class="text-right">
                    @if ($order->trashed())
                        <a href="{{ route('admin.order.show', $order) }}" class="trashed-order-link">
                            {{ $order->order_id }} удален
                        </a>
                    @else
                        <?php
                        $hasOffer = ($order->cart && ($order->cart->cartSetCase->first(function ($index, $cartSetCase) {
                                    return ($cartSetCase->offer_id ? true : false);
                                }) || $order->cart->cartSetProducts->count()));
                        ?>
                        @if ($hasOffer)
                            М
                        @endif
                        <a href="{{ route('admin.order.show', $order) }}">
                            {{ $order->order_id }}
                        </a>
                    @endif
                    @if ($destinationOrder)
                    <a class="btn btn-xs btn-primary" data-toggle="confirmation"
                       data-title="Вы уверены?" href="{{ route('admin.order.swallow', $order) }}">Объединить</a>
                    @endif
                </td>
                <td class="text-right">
                    <ul>
                        @if (isset($order->cart))
                            @foreach ($order->cart->cartSetProducts as $cartSetProduct)
                                <li style="list-style-type: none;">{{ $cartSetProduct->id }}</li>
                            @endforeach
                        @endif
                    </ul>
                    {{--@if($order->cart)
                        <a href="{{ action('Admin@cart', ['f_id' => $order->cart->cart_id]) }}">{{ $order->cart->cart_id }}</a>
                    @endif--}}
                </td>
                <td>{{ $order->order_ts }}</td>
                <td>
                    <select style="width: 100%" autocomplete="off" class="oh-order-status status-colors" data-order-id="{{ $order->order_id }}">
                        @foreach($statuses as $status)
                            <option data-color="{{ $status->status_color }}" value="{{ $status->status_id }}" {{ $order->order_status_id == $status->status_id ? 'selected' : '' }}>{{ $status->status_name }}</option>
                        @endforeach
                    </select>
                </td>
                <td style="width:200px">{{ trans('order.payment_status.' . $order->getPaymentsStatus()) }}</td>
                <td>{{ $order->client_name }}</td>
				<td>{{ (!empty($order->city->city_name_full)) ? $order->city->city_name_full : ""  }}</td>
                <td>
				@if(in_array(mb_substr(str_replace(['+', '-', ' ', '(', ')', '_'], '', $order->client_phone), 1), $phones))
					<b>{{ $order->client_phone }}</b>
				@else
					{{ $order->client_phone }}
				@endif
				</td>
                <td><a href="mailto:{{ $order->client_email }}">{{ $order->client_email }}</a></td>
                {{--<td style="width:300px;word-break: break-all;">{{ $order->utm }}</td>--}}
                <td class="text-right">
                    @if ($order->cart)
                        {{ $order->cart->cartSetCase->count() }}
                    @endif
                </td>
                <td class="text-right">
                    @php
                        $product_sum = 0;
                        if (isset($order->cart))
                            foreach ($order->cart->cartSetProducts as $cartSetProduct)
                                $product_sum += $cartSetProduct->item_count;
                    @endphp
                    @if ($order->cart)
                        {{ $product_sum }}
                    @endif
                </td>
                <td class="text-right">
                    <a href="{{ route('admin.payment.order_list', ['order' => $order]) }}">
                        {{ $order->getTotalSum() }}
                    </a>
                </td>
                <td class="text-center"><a title="Заказ" target="_blank" href="{{ route('admin.order.print', ['order' => $order, 'hash' => $order->order_hash, 'type' => 'admin']) }}"><span class="fa fa-info-circle"></span></a></td>
                {{--<td class="text-center"><a title="Пользователь" target="_blank" href="{{ action('OrderController@orderPrint', ['id' => $order->order_id, 'hash' => $order->order_hash, 'type' => 'adminPrint']) }}"><span class="fa fa-info-circle"></span></a></td>--}}
                <td class="text-center"><a title="Печать" target="_blank" href="{{ route('admin.order.print', ['order' => $order->order_id, 'hash' => $order->order_hash, 'type' => 'adminPrint']) }}"><span class="fa fa-print"></span></a></td>
                <td class="text-center"><a title="Пересобрать картинку" href="{{ route('admin.order.recompile', $order) }}"><span class="fa fa-cogs"></span></a></td>
                <td class="text-center"><a title="Переотправить email" href="{{ route('admin.order.mail', $order) }}"><span class="fa fa-envelope"></span></a></td>
                <td class="text-center"><a class="btn-delete" title="Удалить"><span class="fa fa-trash"></span></a></td>
            </tr>
        @empty
            <tr>
                <td colspan="100%" class="text-center bg-warning"><strong>Ничего нет</strong></td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
{{ $orders->links() }}