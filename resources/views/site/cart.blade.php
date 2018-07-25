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
    @if (! request('full') && ! request('short'))
        <!-- Google Analytics Content Experiment code -->
        <script>function utmx_section(){}function utmx(){}(function(){var
        k='134627699-2',d=document,l=d.location,c=d.cookie;
        if(l.search.indexOf('utm_expid='+k)>0)return;
        function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
        indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
        length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
        '<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
        '://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
        '&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
        valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
        '" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
        </script><script>utmx('url','A/B');</script>
        <!-- End of Google Analytics Content Experiment code -->
    @endif
@endsection
@section('content')
    <div id="cart" class="container-fluid">
        <a class="row add-header" href="{{ url('custom') }}#bg">
            <span class="icon-plus"></span>&nbsp;&nbsp;&nbsp;&nbsp;СОЗДАТЬ ЧЕХОЛ
        </a>
        @if($cartCount == 0)
            <div class="row text-center empty">
                <h2>Корзина пустая</h2>
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
                        <div class="panel-heading">ОФОРМЛЕНИЕ ЗАКАЗА</div>
                        <div class="panel-body order-form">
                            <form id="form" action="{{ url('custom/cart/order') }}" method="post">
                                <div class="row checkout-selector">
                                    @if ($formVisualType == 'full')
                                        {{--<a href="{{ url('custom/cart?full=1') }}" class="col-xs-12 {{ request($formInversionRequest) ? '' : 'active' }}">Полное<span class="hidden-xs"> оформление</span></a>--}}
{{--                                        <a href="{{ url('custom/cart?short=1') }}" class="col-xs-6 {{ request($formInversionRequest) ? 'active' : '' }}">Быстрое<span class="hidden-xs"> оформление</span></a>--}}
                                    @else
                                        {{--<a href="{{ url('custom/cart?short=1') }}" class="col-xs-6 {{ request($formInversionRequest) ? '' : 'active' }}">Быстрое<span class="hidden-xs"> оформление</span></a>--}}
                                        {{--<a href="{{ url('custom/cart?full=1') }}" class="col-xs-12 {{ request($formInversionRequest) ? 'active' : '' }}">Полное<span class="hidden-xs"> оформление</span></a>--}}
                                    @endif
                                </div>
                                @if (($formVisualType == 'short' && !request('full')) || ($formVisualType == 'full' && request('short')))
                                    @include('_partial.js_data')
                                    <input autocomplete="off" class="form-group form-control" name="phone" type="text" id="phone" placeholder="Номер телефона" data-mandatory="true">
                                    @if(request('short') == 2)
                                        <input class="form-group form-control" autocomplete="off" name="email" data-mandatory="true" type="email" id="email" placeholder="Email">
                                    @endif
                                @elseif (($formVisualType == 'full' || !request('short')) || ($formVisualType == 'short' && request('full')))
                                    <h5>Контактные данные</h5>
                                    <input type="hidden" name="full" value="1">
                                    <input class="form-group form-control" autocomplete="off" name="name" data-mandatory="true" type="text" id="name" placeholder="Фамилия, Имя">
                                    <input class="form-group form-control" autocomplete="off" name="email" data-mandatory="true" type="email" id="email" placeholder="Email">
                                    <input class="form-group form-control" autocomplete="off" name="phone" data-mandatory="true" type="text" id="phone" placeholder="Телефон">
                                    <hr />
                                    <h5>Выберите способ доставки</h5>
                                    @include('_partial.delivery_chooser')
                                    <hr />
                                    <h5>Коментарий к заказу</h5>
                                    <div class="form-group">
                                        <textarea class="form-control" name="comment"></textarea>
                                    </div>
                                @endif

                                <hr />
                                <h5 class="type_payment_h5" style="display:none;">Способ оплаты</h5>
                                <div class="form-group gen-offline-pay" style="display: none;">
                                    <label class="cart-cb">
                                        <input type="radio" name="payment_methods_id" value="3">
                                        <span class="point"><span></span></span>
                                        <span class="text">Оплатить при получении</span>
                                    </label>
                                    <div tabindex="0" href="#" data-placement="left"
                                         class="cart-help-icon icon-question pull-right pointer" data-container="body"
                                         data-toggle="popover" data-trigger="focus"
                                         data-html="true" data-content='<div class="help-popover">
                                                    Оплата наличными курьеру при получении
                                                </div>'>
                                    </div>
                                </div>
                                <div class="form-group gen-online-pay" style="display: none;">
                                    <label class="cart-cb">
                                        <input type="radio" name="payment_methods_id" value="1">
                                        <span class="point"><span></span></span>
                                        <span class="text">Оплата онлайн</span>
                                    </label>
                                    <div tabindex="0" href="#" data-placement="left"
                                         class="cart-help-icon icon-question pull-right pointer" data-container="body"
                                         data-toggle="popover" data-trigger="focus"
                                         data-html="true" data-content='<div class="help-popover">
                                                        Оплата онлайн с помощью платежной системы
                                                    </div>'>
                                    </div>
                                </div>
								
                            </form>
                            <div class="cart-total row">
                                <div class="pull-left">ИТОГО:</div>
                                <div class="pull-right text-right">
                                    <div class="total amount"><span class="amount-total"></span><span class="icon-rouble"></span></div>
                                    <div>сумма: <span class="amount"><span class="amount-cost"></span><span class="icon-rouble"></span></span></div>
                                    <div>
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
                                {{--<p class="cart_color_action" style="font-size: 20px; #666a6f">Акция "Кружка в подарок"</p>
                                <p>при покупки двух чехлов <a href="/kruzhki" style="text-decoration: underline">авторская кружка</a> в подарок!</p>--}}
                                <!--<div class="promo">
                                    <div id="promocode_ctl">
                                        <div class="input-group">
                                            <input class="form-group form-control block" autocomplete="off" data-mandatory="true" type="text" id="promocode_value" placeholder="Введите промокод" value="DELIVERY_FREE">
                                            <span class="input-group-btn">
                                                <button type="submit" id="promocode_button" class="btn cart-button">Применить</button>
                                            </span>
                                        </div>
                                        <div id="promocode_error" class="bg-danger text-center m-t-15 p-15 hidden"></div>
                                    </div>
                                </div>-->
                            </form>
                            <?php /*<form id="promocode_form">
                                <p style="color: red; font-size: 25px;">Акция 1+1 = 3</p>
                                <p>Закажи 2 чехла и получи третий в подарок!</p>
                                <div class="promo">
                                    <div id="promocode_ctl">
                                        <div class="input-group">
                                            <input class="form-group form-control block" autocomplete="off" data-mandatory="true" type="text" id="promocode_value" placeholder="Введите промокод" value="HAPPY3">
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
                            </form> */ ?>
                            <hr />
                            <div>
                                <?php /*
									<a href="{{ url('custom') }}" class="cart-checkout cart-button btn-block link" style="padding: 20px; border-radius: 2px; margin-top: 5px; width: 48%; display: block; float: left; background: red;">+ Добавить чехол по акции</a>
								*/ ?>
                                <button id="cart-submit" type="button" class="cart-checkout cart-button btn-block link" style="">
                                    Оформить
                                </button>
                                <br style="clear: both;">
								<a href="/designs/" class="desctop_hide_btn">
									<button type="button" class="cart-checkout cart-button btn-block link">
										ВЫБРАТЬ ЕЩЕ ЧЕХОЛ
									</button>
								</a>
                                <br style="clear: both;">
                            </div>
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
                            <?php /* 
								<a class="cart-button add-button" href="{{ url('custom#bg') }}"><span class="icon-plus"></span>&nbsp;&nbsp;&nbsp;&nbsp;СОЗДАТЬ ЧЕХОЛ</a>
							*/ ?>
                            <a class="cart-button add-button" href="/designs/">
								ВЫБРАТЬ ЕЩЕ ЧЕХОЛ
							</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

<style>
#cart .order-form #cart-submit {
	width: 100%; 
	background-color: #e85854; 
	margin: 0 auto;
	text-transform: uppercase;
}
#cart .order-form #cart-submit:hover{
	background-color: #b20505;
}
.desctop_hide_btn{
	display: none;
}
#cart .order-form .desctop_hide_btn button{
	text-transform: uppercase;
	padding: 20px;
}
@media screen and (max-width: 990px){
	.desctop_hide_btn{
		display: block;
	}
}
</style>