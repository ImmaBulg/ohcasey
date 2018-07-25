@extends('site.layouts.master')
@if ($payment)
    @section('title') Оплата заказа #{{ data_get($payment, 'order.order_id') }}, сумма {{$payment->amount}} р. @endsection
@else
@section('title')Во время оплаты заказа что-то пошло не так@endsection
@endif
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
                <h2>Во время оплаты заказа что-то пошло не так. Не стоит беспокоиться. Свяжитесь с нами и сообщите номер Вашего заказа.</h2>
            </div>
            @if ($payment)
                @include('site.payments.info')
            @endif
        </div>
    </div>
@endsection