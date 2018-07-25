@extends('site.layouts.app')

@section('title', 'Заказ оформлен!')

@section('content')

    <div class="inner" id="order-success">
        <div class="inner__cart">
            <h1 class="h1">Заказ оформлен!</h1>

            <p class="text">Номер вашего заказа: {{$order->order_id}} (номер отправлен на ваш email).  Наш менеджер
                свяжется с вами в ближайшее время и уточнит детали оплаты и доставки. Спасибо за заказ!</p>

            <a href="{{route('shop.index')}}" class="btn">ПРОДОЛЖИТЬ ПОКУПКИ</a>
        </div>
    </div>
@endsection

@if(env('APP_ENV') == 'production')
	@section('fbq-init')
		fbq('init', '1830520020565197', {
			em: '{{ $order->client_email }}',
			ph: '{{ $order->client_phone }}',
			fn: '{{ $order->client_name }}',
			ct: '{{ $order->city ? $order->city->city_name : '' }}'
		}); // Insert your pixel ID here.
	@endsection
@endif

@push('js')
@if(env('APP_ENV') == 'production')
    <script>
        fbq('track', 'InitiateCheckout');
        fbq('track', 'Purchase', {
            content_ids: {{json_encode($fbProducts)}},
            content_type: 'product',
            value: parseInt({{$order->order_amount}}),
            currency: 'RUB',
        });
    </script>
@endif
@endpush