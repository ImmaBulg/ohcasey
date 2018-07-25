@extends('site.layouts.master')
@section('title', 'Ohcasey | Конструктор чехлов | Корзина')
@section('header')
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0"/>
	<script type="application/javascript" src="{{ _el('js/cart.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/bootstrap-confirmation.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/select2.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/select2.ru.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/bootstrap-datepicker.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/bootstrap-datepicker.ru.min.js') }}"></script>
	<script type="application/javascript" src="{{ url('js/validator.js') }}"></script>
	<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
	<link rel="stylesheet" href="{{ url('css/select2.css') }}">
	<link rel="stylesheet" href="{{ url('css/bootstrap-datepicker3.css') }}">
	<link rel="stylesheet" href="{{ _el('css/cart.css') }}">
@endsection
@section('content')
    <div id="cart" class="container-fluid">
        <a class="row add-header" href="{{ url('custom') }}#bg">
            <span class="icon-plus"></span>&nbsp;&nbsp;&nbsp;&nbsp;СОЗДАТЬ ЧЕХОЛ
        </a>
        @if($cartCount == 0)
            <div class="row text-center empty">
                <h1>Корзина пустая</h1>
            </div>
        @else
            <div class="row">
                <div class="col-md-6 col-lg-5 col-lg-offset-1">
                    <h3 class="page-title">Корзина</h3>
                </div>
            </div>
            <?php
            $formVisualType = env('CART_SHORT_FORM') ? 'short' : 'full';
            $formInversionRequest = $formVisualType == 'short' ? 'full' : 'short';
            ?>
            <div class="row">
                <div class="col-md-6 col-lg-5 col-lg-offset-1">
                    <div class="panel">
                        @if (Session::has('wasErrorTryAgain'))
                            @include('site.payments.error')
                        @endif
                        <div class="panel-heading">ОФОРМЛЕНИЕ ЗАКАЗА</div>
                        <div class="panel-body order-form">
                            <form id="form" action="{{ url('custom/cart2/order') }}" method="post">
                                <div class="row checkout-selector">
                                    @if ($formVisualType == 'full')
                                        <a href="{{ url('custom/cart2?full=1') }}" class="col-xs-6 {{ request($formInversionRequest) ? '' : 'active' }}">Полное<span class="hidden-xs"> оформление</span></a>
                                        <a href="{{ url('custom/cart2?short=1') }}" class="col-xs-6 {{ request($formInversionRequest) ? 'active' : '' }}">Быстрое<span class="hidden-xs"> оформление</span></a>
                                    @else
                                        <a href="{{ url('custom/cart2?short=1') }}" class="col-xs-6 {{ request($formInversionRequest) ? '' : 'active' }}">Быстрое<span class="hidden-xs"> оформление</span></a>
                                        <a href="{{ url('custom/cart2?full=1') }}" class="col-xs-6 {{ request($formInversionRequest) ? 'active' : '' }}">Полное<span class="hidden-xs"> оформление</span></a>
                                    @endif
                                </div>
                                @if (($formVisualType == 'short' && !request('full')) || ($formVisualType == 'full' && request('short')))
                                    @include('_partial.js_data')
                                    <input autocomplete="off" class="form-group form-control" name="phone" type="text" id="phone" placeholder="Номер телефона" data-mandatory="true" value="{{$order->client_phone or ''}}">
                                    @if(request('short') == 2)
                                        <input class="form-group form-control" autocomplete="off" name="email" data-mandatory="true" type="email" id="email" placeholder="Email" value="{{$order->client_email or ''}}">
                                    @endif
                                @elseif (($formVisualType == 'full' || !request('short')) || ($formVisualType == 'short' && request('full')))
                                    <input type="hidden" name="full" value="1">
                                    <h3>1. Введите свои данные</h3>
                                    <input class="form-group form-control" autocomplete="off" name="name" data-mandatory="true" type="text" id="name" placeholder="Фамилия, Имя" value="{{$order->client_name or ''}}">
                                    <input class="form-group form-control" autocomplete="off" name="email" data-mandatory="true" type="email" id="email" placeholder="Email" value="{{$order->client_email or ''}}">
                                    <input class="form-group form-control" autocomplete="off" name="phone" data-mandatory="true" type="text" id="phone" placeholder="Телефон" value="{{$order->client_phone or ''}}">
                                    <hr />
                                    <h3>2. Выберите способ доставки</h3>
                                    @include('_partial.delivery_chooser')
                                    <hr />
                                    <h3>3. Выберите способ оплаты</h3>
                                    @foreach($payment_methods as $p)
                                        <div class="payment_method {{$p->name}} {{$order && in_array($p->id, $order->delivery->payment_methods->pluck('id')->toArray()) ? '' : 'hidden'}} form-group">
                                            <label class="cart-cb">
                                                <input name="payment_methods_id" class="{{$p->is_online ? 'online' : 'offline'}}" type="radio" value="{{$p->id}}" data-mandatory="true" {{($order && $order->payment_methods_id == $p->id) ? 'checked' : ''}}>
                                                <span class="point"><span></span></span>
                                                <span class="text">{{$p->description}}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                    <hr />
                                    <h5>Коментарий к заказу</h5>
                                    <div class="form-group">
                                        <textarea class="form-control" name="comment">{{$order->order_comment or ''}}</textarea>
                                    </div>
                                @endif
								{{--<hr />--}}
								{{--<h5>Выберите способ оплаты</h5>--}}
								{{--<div class="form-group">--}}
									{{--<label class="cart-cb">--}}
										{{--<input type="radio" name="payment" value="cash">--}}
										{{--<span><span></span></span>--}}
										{{--Оплата наличными или переводом--}}
									{{--</label>--}}
									{{--<div tabindex="0" href="#" data-placement="left"--}}
										 {{--class="cart-help-icon icon-question pull-right pointer" data-container="body"--}}
										 {{--data-toggle="popover" data-trigger="focus"--}}
										 {{--data-html="true" data-content='<div class="help-popover">--}}
													{{--Оплата наличными или переводом--}}
												{{--</div>'>--}}
									{{--</div>--}}
								{{--</div>--}}
								{{--<div class="form-group">--}}
									{{--<label class="cart-cb">--}}
										{{--<input type="radio" name="payment" value="online">--}}
										{{--<span><span></span></span>--}}
										{{--Оплата онлайн--}}
									{{--</label>--}}
									{{--<div tabindex="0" href="#" data-placement="left"--}}
										 {{--class="cart-help-icon icon-question pull-right pointer" data-container="body"--}}
										 {{--data-toggle="popover" data-trigger="focus"--}}
										 {{--data-html="true" data-content='<div class="help-popover">--}}
														{{--Оплата онлайн с помощью платежной системы--}}
													{{--</div>'>--}}
									{{--</div>--}}
								{{--</div>--}}
							</form>
							<div class="cart-total row">
								<div class="pull-left">ИТОГО:</div>
								<div class="pull-right text-right">
									<div class="total amount"><span class="amount-total"></span><span class="icon-rouble"></span></div>
									<div>сумма: <span class="amount"><span class="amount-cost"></span><span class="icon-rouble"></span></span></div>
									<div id="delivery-info" data-amount-delivery="{{$order ? $order->delivery_amount : ''}}">
										@if (!request('full'))
											<span class="red">*</span> без учета доставки
										@else
											доставка: <span class="amount"><span class="amount-delivery"></span><span class="icon-rouble"></span></span>
										@endif
									</div>
									<div id="amount-discount">скидка: <span class="amount"><span class="amount-discount"></span><span class="icon-rouble"></span></span></div>
								</div>
							</div>
							<form id="promocode_form">
								<div class="promo">
									<div id="promocode_ctl">
										<div class="input-group">
											<input class="form-group form-control block" autocomplete="off" data-mandatory="true" type="text" id="promocode_value" placeholder="Введите промокод">
											<span class="input-group-btn">
												<button type="submit" id="promocode_button" class="btn cart-button">Применить</button>
											</span>
										</div>
										<div id="promocode_error" class="bg-danger text-center m-t-15 p-15 hidden"></div>
									</div>
									<div id="promocode_info" class="hidden">
										<div>Ваш промокод: <strong id="promocode_name"></strong> <span id="promocode_remove" class="icon-cross pointer link pull-right"></span></div>
										<div class="promocode-fail text-danger m-t-15 hidden" id="promocode_cond_count">Количество товара в корзине должно быть не меньше <strong></strong></div>
										<div class="promocode-fail text-danger m-t-15 hidden" id="promocode_cond_amount">Сумма корзины должна быть не меньше <strong></strong><span class="icon-rouble"></span></div>
									</div>
								</div>
							</form>
							<hr />
							<button id="cart-submit" type="button" class="cart-checkout cart-button btn-block link">
								{{($order && $order->payment_method && $order->payment_method->is_online) ? "Оплатить" : "Оформить"}}
							</button>
							<div class="cart-note">
								@if($formVisualType == 'full')
									Наш менеджер свяжется с вами для подтверждения заказа
								@else
									<span class="red">*</span> Наш менеджер свяжется с вами для уточнения способа и сроков доставки
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6 col-lg-5">
					<div class="panel">
						<div class="panel-heading"><span class="cart-count"></span> В КОРЗИНЕ</div>
						<div class="panel-body order-form">
							<div id="devices"></div>
							<a class="cart-button add-button" href="{{ url('custom/#bg') }}"><span class="icon-plus"></span>&nbsp;&nbsp;&nbsp;&nbsp;СОЗДАТЬ ЧЕХОЛ</a>
						</div>
					</div>
				</div>
			</div>
		@endif
	</div>
@endsection
