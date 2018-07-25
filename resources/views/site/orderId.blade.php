@extends('site.layouts.master')
@section('title', 'Ohcasey | Конструктор чехлов | Спасибо за заказ')
@section('header')
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0"/>
@endsection
@section('content')
    <div class="container-fluid text-center" id="order-success">
        <a class="row add-header" href="{{ url('custom') }}#bg">
            <span class="icon-plus"></span>&nbsp;&nbsp;&nbsp;&nbsp;СОЗДАТЬ ЧЕХОЛ
        </a>

        <h1>СПАСИБО, ВАШ ЗАКАЗ ПРИНЯТ!</h1>
        <h2>№ {{ $order->order_id or 'n/a' }}</h2>
        <p>Мы отправили Вам письмо с данными заказа,<br>проверьте, пожалуйста, все ли правильно и ждите нашего звонка.
        </p>
        <a class="instagram_button" href="https://instagram.com/_ohcasey_" title="Наш инстаграм">
            <img src="{{ url('img/instagram.png') }}">
        </a>
    </div>
@endsection

@if(env('APP_ENV') == 'production')
	@section('fbq-init')
		fbq('init', '1830520020565197', {
			em: '{{ $order->client_email }}',
			ph: '{{ $order->client_phone }}',
			fn: '{{ $order->client_name }}',
			ct: '{{ $order->city ? $order->city->city_name : '' }}'
		}); 
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
