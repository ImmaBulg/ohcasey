@extends('admin.layout.master')
@section('content')
    <div style="height:15px;"></div>
    <div class="row">
        <div class="col-md-12">
            <h3>Оплата заказа #{{$order->order_id}}, сумма = {{$order->getTotalSum()}} р. </h3>
            @include('admin.payment.add_form')
        </div>
    </div>
    @include('admin.payment.list', ['payments' => $order->payments])
@endsection