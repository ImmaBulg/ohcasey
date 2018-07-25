@extends('site.layouts.app')

@section('title') Оплата заказа #{{ data_get($payment, 'order.order_id') }}, сумма {{$payment->amount}} р. @endsection

@section('content')
    <div class="inner">
        <div class="inner__cart">
            <h1 class="h1">Заказ #{{ data_get($payment, 'order.order_id') }} успешно оплачен!</h1>
            <p>
                Вы получите смс или письмо как заказ будет готов.
            </p>
            <p>
                Любые вопросы можете задать по телефону.
            </p>
            <a href="{{route('shop.index')}}" class="btn">ПРОДОЛЖИТЬ ПОКУПКИ</a>
        </div>
    </div>
@endsection