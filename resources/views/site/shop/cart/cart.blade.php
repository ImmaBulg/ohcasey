@extends('site.layouts.app')

@section('title', 'Ohcasey')

@section('content')
    <div class="inner">
        <div class="inner__cart">

            <h1 class="h1">Корзина</h1>

            <div class="cart-page clearfix">
                <div class="cart-page__left">

                    <div class="total-order total-order--mobile">
                        <div class="total-order__item">
                            <div class="total-order__text"><span class="js-cart-counter">{{$cartCount}}</span> товара на сумму:</div>
                            <div class="total-order__price price price--sm js-price-value">{{$priceWithDiscount}}</div>
                        </div>
                    </div>
                    <div class="cart-list">
                        <div class="cart-list__item cart-list__item--head">
                            <div class="cart-list__head">Товар</div>
                            <div class="cart-list__head"></div>
                            <div class="cart-list__head">Количество</div>
                            <div class="cart-list__head cart-list__head--right">Цена</div>
                        </div>

                        @foreach ($cartSetCases as $case)
                            @include('site.shop.cart.item.case')
                        @endforeach

                        @foreach ($cartSetProducts as $product)
                            @include('site.shop.cart.item.default')
                        @endforeach
                    </div>
                </div>

                <div class="cart-page__right">
                    <div class="total-order js-total-order">
                        <div class="total-order__item">
                            <div class="total-order__text">Стоимость товаров:</div>
                            <div class="total-order__price price price--sm js-price-without-discount">{{$priceValue}}</div>
                        </div>
                        <div class="total-order__item"{!! !data_get($cart, 'promotionCode.code_discount', '') ? ' style="display:none;"' : '' !!}>
                            <div class="total-order__text">Скидка:</div>
                            <div class="total-order__price price price--sm js-discount-value">{{data_get($cart, 'promotionCode.code_discount', 0)}}</div>
                        </div>
                        <div class="total-order__item">
                            <div class="total-order__text">Подитог:</div>
                            <div class="total-order__price total-order__price--lg price js-price-value">{{$priceWithDiscount}}</div>
                        </div>

                        <a href="{{route('shop.cart.process')}}" class="btn" id="gotocheckout">оформить заказ</a>
						@if(!empty($lastCategoryLink))
						<div class="cart-back-link-wrapper">
							<a class="btn" href="{{ $lastCategoryLink }}">Продолжить покупки</a>
						</div>
						@endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="/js/shopcart.js"></script>
	<script>
		$(function(){
			$(".js-remove-case").click(function(){
				var product_block = $(this).closest(".js-case-container");
				var device = product_block.find(".cart-list__model").html();
				var material = product_block.find(".cart-list__text").eq(1).html();
				var price = product_block.find(".price").html();
				
				var productId = product_block.find('input[name="product-id"]').val();
				var productCategroy = product_block.find('input[name="product-category"]').val();
				var quantity = product_block.find(".js-select").eq(0).val();
				var productName = product_block.find('.cart-list__model').eq(0).html();
				
				dataLayer.push({
					"ecommerce": {
						"remove": {
							"products": [
								{
									"name": device + " " + material,
									"category": material,
									"quantity": 1,
									"variant": device,
									"price": price,
								}
							]
						}
					}
				});
				
				//Яндекс комерция
				dataLayer.push({
					"ecommerce": {
						"remove": {
							"products": [
								{
									"id": productId,
									"name": productName,
									"category": productCategroy,
									"quantity": quantity
								}
							]
						}
					}
				});
				// console.log(device, material, quantity, price, device + " " + material);				
			})
		})
	</script>
@endsection