@extends('site.layouts.app')

@section('title', 'Ohcasey')

@section('content')
    <div class="inner">
        <div class="inner__cart">

            <h1 class="h1">Оплата заказа #{{$payment->order->order_id}}</h1>

            <div class="cart-page clearfix">
                <div class="cart-page__left">

                    <div class="total-order total-order--mobile">
                        <div class="total-order__item">
                            <div class="total-order__text"><span class="js-cart-counter">{{$payment->order->cart->cartSetCase->count() }}</span> товара на сумму:</div>
                            <div class="total-order__price price price--sm js-price-value">{{$payment->order->getTotalSum()}}</div>
                        </div>
                    </div>

                    <div class="cart-list">
                        <div class="cart-list__item cart-list__item--head">
                            <div class="cart-list__head">Товар</div>
                            <div class="cart-list__head"></div>
                            <div class="cart-list__head">{{(count($products) > 0) ? "Количество" : "" }}</div>
                            <div class="cart-list__head cart-list__head--right">Цена</div>
                        </div>

						
						@if( count($payment->order->cart->cartSetCase) > 0)
							@foreach ($payment->order->cart->cartSetCase as $case)
								<div class="cart-list__item js-case-container">
									<div class="cart-list__img">
										<img src="{{route('cart.case.image', ['id' => $case->cart_set_id])}}" alt="">
									</div>
									<div class="cart-list__desc">
										{{--<div class="cart-list__title">Starbucks Fairy</div>--}}
										<div class="cart-list__model">Телефон: {{$case->device->device_caption}}</div>
										@if (isset($case->device->device_colors[$case->item_source['DEVICE']['color']]))
											<div class="cart-list__text">Цвет телефона: <div class="cart-list__color" style="background-color: {{$case->device->device_colors[$case->item_source['DEVICE']['color']]}}"></div></div>
										@endif
										@if ($case->item_source['DEVICE']['casey'] == 'silicone')
											<div class="cart-list__text">Материал: Силикон</div>
										@elseif ($case->item_source['DEVICE']['casey'] == 'plastic')
											<div class="cart-list__text">Материал: Пластик</div>
										@elseif ($case->item_source['DEVICE']['casey'] == 'glitter')
											<div class="cart-list__text">Материал: Жидкий глиттер</div>
										@elseif ($case->item_source['DEVICE']['casey'] == 'glitter_1')
											<div class="cart-list__text">Материал: Жидкий глиттер</div>
										@elseif ($case->item_source['DEVICE']['casey'] == 'glitter_2')
											<div class="cart-list__text">Материал: Жидкий глиттер</div>
										@elseif ($case->item_source['DEVICE']['casey'] == 'glitter_3')
											<div class="cart-list__text">Материал: Жидкий глиттер</div>
										@elseif ($case->item_source['DEVICE']['casey'] == 'glitter_4')
											<div class="cart-list__text">Материал: Жидкий глиттер</div>
										@else
											<div class="cart-list__text">Материал: Soft Touch</div>
										@endif
									</div>
									<div class="cart-list__count">
									</div>
									<div class="cart-list__price"><span class="price">{{$case->item_cost}}</span></div>
								</div>
							@endforeach
							@foreach ($payment->order->cart->cartSetProducts as $product)
								<div class="cart-list__item js-case-container">
									<div class="cart-list__img">
										<img src="{{$product->offer->product->mainPhoto()}}" alt="">
									</div>
									<div class="cart-list__desc">
										<div class="cart-list__title">{{$product->offer->product->name}}</div>
										@if (isset($case->device->device_colors[$case->item_source['DEVICE']['color']]))
											<div class="cart-list__text">{{$product->offer->optionValues->implode(', ')}}</div>
										@endif
									</div>
									<div class="cart-list__count">
									</div>
									<div class="cart-list__price"><span class="price">{{$product->item_cost}}</span></div>
								</div>
							@endforeach
						@endif
                        @if (count($products) > 0)
                            @foreach($products as $product)
                                <div class="cart-list__item js-case-container">
                                    <div class="cart-list__img">
                                        <img src="{{$product[0]->mainPhoto()}}" alt="">
                                    </div>
                                    <div class="cart-list__desc">
                                        <!-- <div class="cart-list__title">{{$product[0]->name}}</div> -->
                                        @if (mb_strpos(mb_strtolower($product[0]->name), 'силикон') !== false)
                                            @php preg_match('/iphone\s[0-9X]{0,1}\s*(plus)*/', mb_strtolower($product[0]->name), $match) @endphp
                                            <div class="cart-list__model">Телефон: {{mb_strtoupper(mb_substr($match[0], 0, 2)) . mb_substr($match[0], 2)}}</div>
                                            @if ((mb_strpos(mb_strtolower($product[0]->name), 'iphone x') === false))
                                                <div class="cart-list__text">Цвет чехла: {{ preg_replace("/.* iphone\s*\d{0,1}\s*(plus)*\s*(цвета)*\s*/", "", mb_strtolower($product[0]->name)) }} </div>
                                            @else
                                                @php preg_match("/^\S*/", mb_strtolower($product[0]->name), $match) @endphp
                                                <div class="cart-list__text">Цвет чехла: {{ $match[0] }} </div>
                                            @endif
                                            <div class="cart-list__text">Материал: cиликон</div>
                                        @endif
                                    </div>
                                    <div class="cart-list__count">{{ $product[1] }} шт.</div>
                                    <div class="cart-list__price"><span class="price">{{$product[0]->price}}</span></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="cart-page__right">
                    <div class="total-order js-total-order">
                        <div class="total-order__item">
                            <div class="total-order__text">Стоимость товаров:</div>
                            <div class="total-order__price price price--sm js-price-without-discount">{{$payment->order->getTotalSum() - $payment->order->delivery_amount}}</div>
                        </div>
                        <div class="total-order__item">
                            <div class="total-order__text">Стоимость доставки:</div>
                            <div class="total-order__price price price--sm js-price-without-discount">{{$payment->order->delivery_amount}}</div>
                        </div>
                        @if ($payment->order->getPaymentsStatus() == \App\Models\Order::PARTIAL_PAYMENTS_PAID)
                            <?php
                            $amount = $payment->order->payments->sum(function (\App\Models\Payment $payment) {
                                return $payment->is_paid ? $payment->amount : 0;
                            });
                            $result = $payment->order->getTotalSum() - $amount;
                            ?>
                            <div class="total-order__item">
                                <div class="total-order__text">Оплачено:</div>
                                <div class="total-order__price price price--sm js-price-without-discount">{{$amount}}</div>
                            </div>
                            <div class="total-order__item">
                                <div class="total-order__text">Остаток:</div>
                                <div class="total-order__price price price--sm js-price-without-discount">{{$result}}</div>
                            </div>
                        @endif
                        <div class="total-order__item">
                            <div class="total-order__text">Оплата на:</div>
                            <div class="total-order__price total-order__price--lg price js-price-value">{{$payment->amount}}</div>
                        </div>

                        <form method="POST" action="{{ route('payment.process_pay', ['paymentHash' => $payment->hash]) }}">
                            <input type="submit" value="Оплатить" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="/js/shopcart.js"></script>
@endsection