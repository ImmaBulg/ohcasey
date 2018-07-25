@extends('site.layouts.master')
@section('title') Оплата заказа #{{ data_get($payment, 'order.order_id') }}, сумма {{$payment->amount}} р. @endsection
@section('header')
    <link rel="stylesheet" href="{{ _el('css/payment.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        <a class="row add-header" href="{{ url('custom') }}#bg">
            <span class="icon-plus"></span>&nbsp;&nbsp;&nbsp;&nbsp;СОЗДАТЬ ЧЕХОЛ
        </a>
        <div class="container">
            <div class="row">
                <h2>Заказ #{{ data_get($payment, 'order.order_id') }} успешно оплачен!</h2>
                <p>
                    Вы получите смс или письмо как заказ будет готов.
                </p>
                <p>
                    Любые вопросы можете задать по телефону.
                </p>
            </div>
        </div>
    </div>
@endsection