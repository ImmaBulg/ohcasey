@extends('site.layouts.app')

@section('title', 'Ohcasey')

@section('content')
    <style>
        .failure-validate {
            color:red !important
        }
        .maxwdth991 {
            display: none;
        }
        @media screen and (max-width: 991px) {
            .maxwdth991 {
                display: block !important;
            }
        }
    </style>

    <div class="inner">
        <div class="inner__cart">
            <form autocomplete="off" method="POST" action="{{ route('cart.order.create') }}" id="shop-form">
                <input type="hidden" name="new_cart" value="1" />
                <input type="hidden" name="country" id="country" value="RU" />
                <input type="hidden" name="pvz" id="pvz" value="" />
                <input type="hidden" name="city" id="city" value="44" />
                <input type="hidden" name="delivery_amount" id="delivery_amount" value="{{$cart->delivery_amount}}" />
                <input type="hidden" name="pickpoint_address" id="pickpoint_address" value="" />

                <a href="{{route('shop.cart.items')}}" class="back">Корзина</a>
                <h1 class="h1">Оформление заказа</h1>

                <ul class="tabs__btns">
                    <li class="tabs__item">
                        <a href="{{ route('shop.cart.process', ['full' => 1]) }}" class="tabs__link{{!$isShort ? ' is-active' : ''}}" style="font-size: 13px">Полное оформление</a> 
                    </li>
                    {{--<li class="tabs__item">
                        <a href="{{ route('shop.cart.process', ['short' => 1]) }}" class="tabs__link{{$isShort ? ' is-active' : ''}}" style="font-size: 13px">Быстрое оформление</a>
                    </li>--}}
                </ul> 
				

                <div class="cart-page  cart-page--order clearfix">
                    <div class="cart-page__left">
                        <div class="total-order total-order--mobile">
                            <div class="total-order__item">
                                <div class="total-order__text"><span class="js-cart-counter">{{$cartCount}}</span> товара на сумму:</div>
                                <div class="total-order__price price price--sm">{{$priceWithDiscount}}</div>
                            </div>
                        </div>

                        <div class="h2">Контактная информация</div>

                        <div class="form">
                            @if (!$isShort)
                                <div class="form__field">
                                    <label for="" class="form__label" id="input-name">Ваше имя</label>
                                    <input class="form__input" type="text" id="name" name="name">
                                </div>

                                <div class="form__field">
                                    <label for="" class="form__label" id="input-email">Email</label>
                                    <input class="form__input" type="email" name="email" id="email">
                                </div>
                            @endif

                            <div class="form__field form__field--phone">
                                <label for="" class="form__label" id="input-phone">Телефон</label>
                                <input class="form__input js-phone-mask"  maxlength="17" id="phone" name="phone" type="tel">
                            </div>
                        </div>

                        @if (!$isShort)
                            <div class="h2" id="input-deliveries">Доставка</div>

                            <div class="radio">
                                <div class="radio__item js-delivery-item">
                                    <?php /*<div class="radio__top">
                                        <div class="radio__left">
                                            <div class="radio__title">
                                                <span>Самовывоз из шоурума в Москве (100% предоплата)</span>
                                                <input name="delivery_type" value="showroom" class="radio__input js-delivery" type="radio">
                                                <span class="radio__mark"></span>
                                            </div>
                                            <div class="radio__text">Будни с 11 до 20. Выходные 12 до 18.<br>ул. Таганская, 24.</div>
                                        </div>

                                        <div class="radio__right">
                                            <div class="radio__title js-delivery-price-showroom" data-price="0">бесплатно</div>
                                        </div>
                                    </div> */?>
                                </div>

                                <div class="radio__item js-delivery-item">
                                    <div class="radio__top">
                                        <div class="radio__left">
                                            <div class="radio__title">
                                                <span>Курьером по Москве</span>
                                                <input name="delivery_type" value="courier_moscow" class="radio__input js-delivery" type="radio">
                                                <span class="radio__mark"></span>
                                            </div>
                                            <div class="radio__text">Ближайшая дата доставки &ndash; {{\Carbon\Carbon::now()->addDays(2)->format('d.m.Y')}}.<br>Наш менеджер свяжется с вами для уточнения даты и времени доставки</div>
                                        </div>

                                        <div class="radio__right">
                                            <div class="radio__title"><div class="price js-delivery-price-courier_moscow" data-price="{{$deliveryTypes['courier_moscow']->delivery_cost}}">{{$deliveryTypes['courier_moscow']->delivery_cost}}</div></div>
                                        </div>
                                    </div>

                                    <div class="radio__hidden js-delivery-hidden">
                                        <div class="form">
                                            <div class="form__field">
                                                <label for="" class="form__label" id="input_courier_moscow_address">Укажите адрес доставки:</label>
                                                <input name="courier_moscow_address" class="form__input" placeholder="Улица, номер дома, этаж, квартира, домофон и т.д." type="text">
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="radio__hidden js-delivery-date-hidden">
                                        <label for="" class="form__label" id="input_delivery_date">Укажите дату визита</label>
                                        <input autocomplete="off" type="text" class="form__input jd-delivery-date-select"
                                               name="delivery_date_courier_moscow" placeholder="Дата визита">
                                    </div>--}}
                                </div>

                                <div class="radio__item js-delivery-item" id="pickpoint-container">
                                    <div class="radio__top">
                                        <div class="radio__left">
                                            <div class="radio__title">
                                                <span id="input_pickpoint_address">По России в пункт выдачи</span>
                                                <input name="delivery_type" value="pickpoint" class="radio__input js-delivery" type="radio">
                                                <span class="radio__mark"></span>
                                            </div>
                                            <div class="radio__text">Ближайшая дата доставки &ndash; <span class="js-delivery-date-136">{{\Carbon\Carbon::now()->addDays(2)->format('d.m.Y')}}</span>.</div>
                                        </div>

                                        <div class="radio__right">
                                            <div class="radio__title"><div class="price js-delivery-cost-136 js-delivery-price-pickpoint" data-price="0">от 125</div></div>
                                        </div>
                                    </div>

                                    <div class="radio__hidden js-delivery-hidden">
                                        <div class="form js-delivery-form">
                                            <div class="form__field suggest-wrap">
                                                <label for="" class="form__label">Укажите город:</label>
                                                <div class="row-city form-group select">
                                                    <select autocomplete="off" placeholder="Выберите ваш город"
                                                            data-mandatory-function="row.country == 'RU'"
                                                            class="select2 error-next js-city js-pickpoint-city" style="width: 100%">
                                                            <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <a href="#" class="radio__link-map js-delivery-next js-popup-open" data-popup="delivery" style="display: none">Выбрать пункт выдачи</a>

                                            <div class="radio__point js-point">Пункт выдачи: <a class="js-point-address js-popup-open" data-popup="delivery"></a></div>
                                        </div>
                                    </div>

                                    {{--<div class="radio__hidden js-delivery-date-hidden">
                                        <label for="" class="form__label" id="input_delivery_date">Укажите дату визита</label>
                                        <input autocomplete="off" type="text" class="form__input jd-delivery-date-select"
                                               name="delivery_date_pickpoint" placeholder="Дата визита">
                                    </div>--}}
                                </div>

                                <div class="radio__item js-delivery-item">
                                    <div class="radio__top">
                                        <div class="radio__left">
                                            <div class="radio__title">
                                                <span id="input_courier_address">По России курьером до двери</span>
                                                <input name="delivery_type" value="courier" class="radio__input js-delivery" type="radio">
                                                <span class="radio__mark"></span>
                                            </div>
                                            <div class="radio__text">Ближайшая дата доставки &ndash; <span class="js-delivery-date-137">{{\Carbon\Carbon::now()->addDays(2)->format('d.m.Y')}}</span></div>
                                        </div>

                                        <div class="radio__right">
                                            <div class="radio__title"><div class="price js-delivery-cost-137 js-delivery-price-courier" data-price="0">от 250</div></div>
                                        </div>
                                    </div>

                                    <div class="radio__hidden js-delivery-hidden">
                                        <div class="form js-delivery-form">
                                            <div class="form__field suggest-wrap">
                                                <label for="" class="form__label">Укажите город:</label>
                                                <div class="row-city form-group select">
                                                    <select autocomplete="off" placeholder="Выберите ваш город"
                                                            data-mandatory-function="row.country == 'RU'"
                                                            class="select2 error-next js-city" style="width: 100%">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form__field">
                                                <label for="" class="form__label">Укажите адрес доставки:</label>
                                                <input class="form__input" name="courier_address" placeholder="Улица, номер дома, этаж, квартира, домофон и т.д." type="text">
                                            </div>
                                        </div>
                                    </div>

                                    {{--<div class="radio__hidden js-delivery-date-hidden">
                                        <label for="" class="form__label" id="input_delivery_date">Укажите дату визита</label>
                                        <input autocomplete="off" type="text" class="form__input jd-delivery-date-select"
                                               name="delivery_date_courier" placeholder="Дата визита">
                                    </div>--}}
                                </div>

                                <div class="radio__item js-delivery-item">
                                    <div class="radio__top">
                                        <div class="radio__left">
                                            <div class="radio__title">
                                                <span id="input_post_address">По всему миру Почтой России</span>
                                                <input name="delivery_type" value="post" class="radio__input js-delivery" type="radio">
                                                <span class="radio__mark"></span>
                                            </div>
                                            <div class="radio__text">7 - 15 дней</div>
                                        </div>

                                        <div class="radio__right">
                                            <div class="radio__title"><div class="price js-delivery-price-post" data-price="200">от 200</div></div>
                                        </div>
                                    </div>

                                    <div class="radio__hidden js-delivery-hidden">
                                        <div class="form js-delivery-form">
                                            <div class="form__field suggest-wrap">
                                                <label for="" class="form__label">Укажите страну:</label>

                                                <div class="row-country form-group select">
                                                    <select autocomplete="off" placeholder="Выберите ваш страну"
                                                            data-mandatory-function="row.country == 'RU'"
                                                            class="select2 error-next js-country" style="width: 100%">
                                                        <option value=""></option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form__field">
                                                <label for="" class="form__label">Укажите индекс:</label>
                                                <input class="form__input js-suggest" name="post_code" placeholder="Индекс" autocomplete="off" type="text">
                                            </div>

                                            <div class="form__field">
                                                <label for="" class="form__label">Укажите адрес:</label>
                                                <input class="form__input js-suggest" name="post_address" placeholder="Адрес" autocomplete="off" type="text">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="h2 js-payment-title">Оплата</div>

                            <div class="radio js-payment">
                                <div class="radio__item js-delivery-item js-payment-method js-payment-method-{{$payments['cash']->id}}">
                                    <div class="radio__top">
                                        <div class="radio__left">
                                            <div class="radio__title">
                                                <span>Наличными курьеру</span>
                                                <input name="payment_methods_id" value="{{$payments['cash']->id}}" class="radio__input js-payment-input" type="radio">
                                                <span class="radio__mark"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="radio__item js-delivery-item js-payment-method js-payment-method-{{$payments['online']->id}}">
                                    <div class="radio__top">
                                        <div class="radio__left">
                                            <div class="radio__title">
                                                <span>Оплата онлайн</span>
                                                <input name="payment_methods_id" value="{{$payments['online']->id}}" class="js-payment-input-online radio__input  js-payment-input" type="radio">
                                                <span class="radio__mark"></span>
                                                <span class="radio__pic"><img src="/img/layout/visa-master.png" alt=""><span>
                                                    <span class="radio__pic"><img src="/img/layout/yam.png" alt=""><span>
                                                        <span class="radio__pic"><img src="/img/layout/qiwi.png" alt=""><span>
                                                    </span></span></span></span></span></span></div>
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="radio__item js-delivery-item js-payment-method js-payment-method-{{$payments['manager']->id}}">
                                    <div class="radio__top">
                                        <div class="radio__left">
                                            <div class="radio__title">
                                                <span>Оплатить при получении</span>
                                                <input name="payment_methods_id" value="{{$payments['manager']->id}}" class="radio__input js-delivery js-payment-input" type="radio">
                                                <span class="radio__mark"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                            </div>
                        @endif

                        <button type="submit" class="btn js-order-btn maxwdth991" id="makeorder">оформить заказ</button>

                        @if (!$isShort)
                            <div class="h2">Комментарий к заказу</div>

                            <div class="form">
                                <div class="form__field form__field--area">
                                    <textarea class="form__textarea" name="comment"></textarea>
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="cart-page__right">
                        <div class="total-order js-total-order" style="position: static; top: 30px;">
                            <div class="total-order__item">
                                <div class="total-order__text">Итого:</div>
                                <div class="total-order__price price price--sm js-price-without-discount">{{$priceValue}}</div>
                            </div>

                            <div class="total-order__item">
                                <div class="total-order__text">Доставка:</div>
                                <div class="total-order__price price price--sm js-delivery-price">{{data_get($cart, 'delivery_amount', '')}}</div>
                            </div>

                            <div style="display:none;" class="total-order__item"{!! !data_get($cart, 'promotionCode.code_discount', '') ? ' style="display:none;"' : '' !!}>
                                <div class="total-order__text">Скидка:</div>
                                <div class="total-order__price price price--sm js-discount-value">{{data_get($cart, 'promotionCode.code_discount', '')}}</div>
                            </div>

                            <div class="total-order__item">
                                <div class="total-order__text">К оплате:</div>
                                <div class="total-order__price total-order__price--lg js-price-total">{{$priceWithDiscount}}</div>
                            </div>

                            <button type="submit" class="btn js-order-btn" id="makeorder">оформить заказ</button>

                            <div class="promo js-promo{{$cart->promotionCode ? ' is-success' : ' is-input'}}">
                                <div class="promo__success">Промокод активирован!</div>
                                {{--<a class="promo__success js-remove-promo" style="color: #bf8a7e" href="#">удалить промокод</a>--}}
                                <a class="promo__link js-promo-link">Ввести промокод</a>
                                <div class="promo__form">
                                    <input class="promo__input" id="promo-input" placeholder="Промокод" type="text">
                                    <button type="button" class="promo__btn js-promo-btn">OK</button>
                                </div>
                                <div class="promo__error">Промокод недействителен</div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="popup-error-validate">
        <div class="popup-error">
            <div class="head_popup">
                <span>Ошибка!</span>
            </div>
            <div class="body_popup">
                <span class="body_popup__text"></span>
            </div>
            <div class="footer-popup">
                <div class="btn">
                    OK
                </div>
            </div>
        </div>
    </div>
    <div class="popup-check-validate">
        <div class="popup-check">
            <div class="head_popup">
                <span>Проверьте данные</span>
            </div>
            <div class="body_popup">
                <span class="body_popup__text"></span>
            </div>
            <div class="footer-popup">
                <div class="btn btn_ok">
                    Все верно
                </div>
                <div class="btn btn_edit">
                    Изменить
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        window.deliveryPaymentsMethod = {!! json_encode($deliveryPayments) !!};
        window.isShortShopCart = {!! json_encode($isShort) !!};
    </script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script src="/js/shopcart.js"></script>

    <div class="popup js-popup" data-popup="delivery">
        <a href="#" class="popup__close js-popup-close"></a>
        <div class="h2">Выберите пункт выдачи</div>
        <div class="map" id="map"></div>
    </div>
	
	<script type="text/javascript">
		$(document).ready(function(){
			if(window.screen.width <= 680){
				$("#map").css("height", $(window).height() + "px");
			}
			
			$('.js-payment-input[value="2"]').parent().hide();
			
			$('input[name="delivery_type"]').change(function(){
				if($(this).val() == "courier_moscow"){
					$('.js-payment-input[value="2"]').parent().show();
				}else{
					$('.js-payment-input[value="2"]').parent().hide();
				}
			});
			
			/* $('input[name="delivery_type"]').change(function(){
				if($(this).val() == "pickpoint"){
					$('.js-payment-input[value="3"]').parent().hide();
				}else{
					$('.js-payment-input[value="3"]').parent().show();
				}
			}); */
		});
		window.cart_id = '{{ $cartSetCases->first()->cart_id or $cartSetProducts->first()->cart_id }}'; 
		window.orderProducts = [
			@foreach($cartSetCases as $cartSetCase)
			{
				id: {{ $cartSetCase->offer ? $cartSetCase->offer->product->id : $cartSetCase->cart_id }},
				name: '{{ $cartSetCase->offer ? $cartSetCase->offer->product->name : '' }}',
				price: {{ $cartSetCase->item_cost }},
				category: '{{ $cartSetCase->offer ? $cartSetCase->offer->product->firstCategory()->name : $cartSetCase->item_source["DEVICE"]["device"] }}',
				coupon: '{{ $cartSetCase->cart->promotionCode ? $cartSetCase->cart->promotionCode->code_name : '' }}',
				quantity: {{ $cartSetCase->item_count }},
				variant: '{{ $cartSetCase->case_name }}'
			},
			@endforeach
			@foreach($cartSetProducts as $cartSetProduct)
			{
				id: {{ $cartSetProduct->offer->product->id }},
				name: '{{ $cartSetProduct->offer->product->name }}',
				price: {{ $cartSetProduct->item_cost }},
				category: '{{ $cartSetProduct->offer->product->firstCategory()->name }}',
				coupon: '{{ $cartSetProduct->cart->promotionCode ? $cartSetProduct->cart->promotionCode->code_name : '' }}',
				quantity: {{ $cartSetProduct->item_count }},
				variant: ''
			},
			@endforeach
		];
	</script>
@endsection