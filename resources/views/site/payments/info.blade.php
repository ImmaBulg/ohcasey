<div class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
            @if ($payment->order->client_name)
                <p style="font-size: 16px;">Фио: {{ $payment->order->client_name }}</p>
            @endif
            @if ($payment->order->client_phone)
                <p style="font-size: 16px;">Телефон: {{ $payment->order->client_phone }}</p>
            @endif
            @if ($payment->order->client_email)
                <p style="font-size: 16px;">E-mail: {{ $payment->order->client_email }}</p>
            @endif
            @if ($payment->order->order_comment)
                <p style="font-size: 16px;">Комментарий: {{ $payment->order->order_comment }}</p>
            @endif
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
        @foreach (data_get($payment, 'order.cart.cartSetCase', collect()) as $case)
            <div class="col-md-3">
                <img src="{{route('orders.showImage', [
                                'order' => $payment->order,
                                'hash'  => $payment->order->order_hash,
                                'img'   => 'item_' . $case->cart_set_id . '.png'
                            ])}}">
                <p style="font-size: 16px;"><span>Цена: {{$case->item_cost}}</span> <span class="icon-rouble"></span></p>
            </div>
        @endforeach
    </div>
</div>
@if ($payment->order->specialItems->count())
    <div class="row">
        <div class="col-md-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
            <h3>Дополнительные товары:</h3>
            @foreach ($payment->order->specialItems as $specialItem)
                <p>{{$specialItem->name}}: {{$specialItem->price}}<span class="icon-rouble"></span></p>
            @endforeach
        </div>
    </div>
@endif
<div class="row">
    <div class="col-md-6 col-md-offset-3 col-sm-offset-3 col-xs-offset-1">
        <h3>Доставка:</h3>
        @include('_partial.delivery_info', [
            'order' => $payment->order,
            'short' => true,
        ])
    </div>
</div>