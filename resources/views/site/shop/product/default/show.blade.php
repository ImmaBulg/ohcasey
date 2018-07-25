@extends('site.layouts.app')

@section('title', $product->title)
@section('keywords', $product->keywords)
@section('description', strip_tags($product->description))

@section('content')
    <div class="inner inner--banner-bottom inner-popup">
        <style>
            .card:after {
                content: "";
                display: block;
                clear: both;
            }
        </style>
        <!-- Add to cart product modal view-->
        <script type="text/x-template" id="modal-template">
            <div class="popup js-popup popup--cart">
                <a @click="$emit('close')" class="popup__close js-popup-close"></a>
                <div class="h2">В корзине</div>
                <div class="cart-list">
                    <div class="cart-list__item cart-list__item--head">
                        <div class="cart-list__head">Товар</div>
                        <div class="cart-list__head"></div>
                        <div class="cart-list__head">Количество</div>
                        <div class="cart-list__head cart-list__head--right">Цена</div>
                    </div>

                    <div class="cart-list__body js-scroll-popup">
                        <div class="cart-list__item" v-for="item in cart">
                            <div class="cart-list__img">
                                <img v-if="item.item_source"
                                     :src="'/api/product/image?bgName=' + item.item_source.DEVICE.bg + '&deviceName=' + item.device_name + '&deviceColorIndex=' + item.item_source.DEVICE.color + '&caseFileName=' + item.item_source.DEVICE.casey"
                                     alt="">
                                <img v-if="!item.item_source" :src="item.offer.product.main_photo_url" alt="">
                            </div>
                            <div class="cart-list__desc">
                                <div class="cart-list__title" v-if="item.offer">@{{ item.offer.product.name }}</div>
                                <div class="cart-list__title" v-if="!item.offer">@{{ item.device.device_caption }}</div>

                                <div class="cart-list__model" v-if="item.item_source">
                                    Телефон: @{{ item.device.device_caption }}</div>
                                <div class="cart-list__text" v-if="item.item_source">Цвет телефона:
                                    <div class="cart-list__color"
                                         :style="'background-color: ' + item.device.device_colors[item.item_source.DEVICE.color]"></div>
                                </div>
                                <div class="cart-list__text" v-if="item.item_source">
                                    Материал: @{{ item.item_source.DEVICE.casey }}</div>
                                <div class="cart-list__text" v-if="!item.item_source"
                                     v-html="item.offer.product.description"></div>
                            </div>
                            <div class="cart-list__count">@{{ item.item_count }}</div>
                            <div class="cart-list__price">
                                <span class="price">@{{ item.item_cost }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="popup__bottom">
                    <a class="btn" href="{{url('/cart')}}" id="gotomodal">ПЕРЕЙТИ В КОРЗИНУ</a>
                </div>
            </div>
        </script>
		
        <div class="container" id="app">

            @include('site.shop.partial.breadcrumbs')

            <div class="card">
                <div class="slider">
                    <div class="slider__main js-slider-for">
                        @foreach ($product->photos as $photo)
                            <div>
                                <img src="{{$photo->url}}" alt="{{$product->name}}" title="{{$product->name}}">
                            </div>
                        @endforeach
                    </div>

                    <div class="slider__nav js-slider-nav">
                        @foreach ($product->photos as $photo)
                            <div>
                                <img src="{{$photo->url}}" alt="{{$product->name}}" title="{{$product->name}}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-info">
                    <h1 class="h1">{{$product->name}}</h1>

                <!--<div class="card-info__price price">{{$product->price}}</div>-->
                    <div class="card-info__price">
                        @if (!empty($product->discount))
                            <div class="price price--old">{{$product->discount}}</div>
                        @endif
                        <span class="price">{{$product->price}}</span>
                    </div>

                    <?php /*@if ($product->offers->count() > 1) */ ?>
					@if ( isset($product->offers) || $product->offers->count() > 1)
                        <div class="card-info__section">
                            <div class="card-info__label">{{$product->optionGroup->options->pluck('name')->implode(', ')}}
                                :
                            </div>
                            <div class="card-info__body">
                                <div class="select">
                                    <select id="offer_id" class="js-select-item" name="offer_id">
                                        @foreach ($product->offers as $offer)
                                            <option value="{{$offer->id}}">{{$offer->optionValues->implode('title', ', ')}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @else
                        <input type="hidden" name="offer_id" id="offer_id" value="{{$product->offers->first()->id}}">
                    @endif

                    <div class="card-info__section card-info__section--btn">
                        <div class="js-add-to-cart-container">
                            <div class="btn" @click="addToCart()" id="addtocartbutton">ДОБАВИТЬ В КОРЗИНУ</div>
                        </div>
                        <div id="offer-in-cart" style="display: none;">
                            <div class="card-info__warning">Товар в корзине!</div>
                            <a class="btn" href="{{route('shop.cart.items')}}" id="gotocart">ОФОРМИТЬ ЗАКАЗ</a>
                        </div>
                    </div>
                    <div class="card-info__section card-info__section--text">
                        <div class="card-info__title">Описание</div>
                        <div class="card-info__text">
                            {!! $product->description !!}
                        </div>
                    </div>

                <!--{{-- @include('site.shop.product._partial.delivery_info') --}}
                @if(!empty($product->optionGroup->delivery_info))
                    {!!$product->optionGroup->delivery_info!!}
                    @endif-->
                </div>
            </div>

            <modal v-if="showModal" @close="showModal = false" :cart="cart"></modal>

            @if (count($product->related) != 0)
                <div class="similar">
                    <h2 class="h2">С этим товаром покупают</h2>

                    <ul class="similar-list">
                        @foreach ($product->related as $related)
                            <li class="similar-list__item">
                                <a href="{{route('shop.product.show', $related->id)}}" class="similar-list__link">
                                    <?php $previewPhotoImage = $related->mainPhoto(); ?>
                                    @if ($previewPhotoImage)
                                        <span class="similar-list__img">
                                    <img src="{{$previewPhotoImage}}" alt="{{ $related->name }}">
                                </span>
                                    @endif
                                    <span class="similar-list__title">{{ $related->name }}</span>
                                    <span class="similar-list__price price">{{ $related->price }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
        @endif
        <!--<div class="promo-block">
                <div class="promo-block__img"><img src="/img/layout/img2.png" alt=""></div>

                <div class="promo-block__info">
                    <div class="h1">Силиконовый чехол</div>
                    <p class="promo-block__text">Благодаря сочетанию силиконовых бортиков и прозрачного пластика чехол не растягивается со временем. Отлично защищает при падениях, не добавляет значительно объема телефону и не скользит в руке.</p>
                </div>
            </div>

            <ul class="promo-list">
                <li class="promo-list__item">
                    <span class="promo-list__img"><img src="/img/layout/oval.png" alt=""></span>
                    <span class="promo-list__title">Защита камеры</span>
                    <span class="promo-list__text">Толщина пластика задней стенки чехла обеспечивает защиту камеры IPhone при падениях</span>
                </li>

                <li class="promo-list__item">
                    <span class="promo-list__img"><img src="/img/layout/oval2.png" alt=""></span>
                    <span class="promo-list__title">Заглушки</span>
                    <span class="promo-list__text">Полезные заглушки для предотвращения попадания пыли, грязи и влаги в функциональные разъемы</span>
                </li>

                <li class="promo-list__item">
                    <span class="promo-list__img"><img src="/img/layout/oval3.png" alt=""></span>
                    <span class="promo-list__title">Литые кнопки</span>
                    <span class="promo-list__text">Комфортный доступ к кнопкам IPhone</span>
                </li>

                <li class="promo-list__item">
                    <span class="promo-list__img"><img src="/img/layout/oval4.png" alt=""></span>
                    <span class="promo-list__title">Защита экрана</span>
                    <span class="promo-list__text">Силиконовые бортики слегка выступают над экраном, защищая его при падениях</span>
                </li>
            </ul>-->
        </div>
        @if (isset($product->htmlDescription))
        {!! $product->htmlDescription !!}
        @endif
        <!--<div class="banner banner--change-bg" style="background-image: url('/img/layout/bg-paint.png')">
            <div class="banner__small" style="background-image: url('/img/layout/bg-paint-small.png')"></div>
            <div class="container">
                <div class="banner__inner">
                    <div class="banner__title h1">Эффект ручной росписи</div>
                    <div class="banner__text">Уникальная технология выпуклой печати создает потрясающий эффект ручной росписи чехла красками. Изображения на чехлах рельефны на ощупь, яркие и стойкие</div>
                </div>
            </div>
        </div>-->

    </div>
@endsection
@push('js')
<script src="/js/frontcommons.js"></script>
<script src="{{url('js/shop_default_product.js')}}"></script>
@if(env('APP_ENV') == 'production')
    <script>
        fbq('track', 'ViewContent', {
            content_ids: [{{$product->id}}],
            content_type: 'product',
            value: {{$product->price}},
            currency: 'RUB'
        });
        $('#addtocartbutton').click(function () {
            fbq('track', 'AddToCart', {
                content_ids: [{{$product->id}}],
                content_type: 'product',
                value: {{$product->price}},
                currency: 'RUB'
            });
        });
    </script>
@endif
@endpush


@push('js')
<script>
$(document).ready(function(){
	var category = $(".breadcrumbs__link").last().html();
	dataLayer.push({
	  'ecommerce': {
		'detail': {
		  'actionField': {},
		  'products': [{
			'name': '{{$product->name}}',         
			'id': '{{$product->id}}',
			'price': '{{$product->price}}',
			'brand': 'brand',
			'category': category,
			'variant': ""
		   }]
		 }
	   }
	});
});	
</script>
@endpush