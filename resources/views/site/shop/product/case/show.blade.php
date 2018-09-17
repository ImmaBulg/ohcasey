@extends('site.layouts.app')

<?php /*
@section('title', 'Чехол для iPhone и Samsung Galaxy «'.$product->name.'» (материал '.$case_caption.') купить в интернет-магазине')
@section('description', strip_tags($product->description))
@section('keywords', $product->keywords)
*/ ?>
@section('title', "Чехол для $device_caption $product->name материал $case_caption")
@section('keywords', "Чехол для $device_caption $product->name материал $case_caption")
@section('description', strip_tags($product->description))

@section('content')
    @php
        $deivces = [];
    @endphp
    <div class="inner inner--banner-bottom inner-popup">
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
                    <div class="slider__main js-slider-for" v-cloak>
                        <div v-if="photos.offer"><img :src="photos.offer" title="Чехол для {{ $device_caption }} {{$product->name}} материал {{ $case_caption }}" alt="Чехол для {{ $device_caption }} {{$product->name}} - Ohcasey"></div>
                        <div v-for="p in photos.product"><img :src="p.url" title="Чехол для {{ $device_caption }} {{$product->name}} материал {{ $case_caption }}" alt="Чехол для {{ $device_caption }} {{$product->name}} - Ohcasey"></div>
                    </div>

                    <div class="slider__nav js-slider-nav" v-cloak>
                        <div v-if="photos.offer"><img :src="photos.offer" title="Чехол для {{ $device_caption }} {{$product->name}} материал {{ $case_caption }}" alt="Чехол для {{ $device_caption }} {{$product->name}} - Ohcasey"></div>
                        <div v-for="p in photos.product"><img :src="p.url" title="Чехол для {{ $device_caption }} {{$product->name}} материал {{ $case_caption }}" alt="Чехол для {{ $device_caption }} {{$product->name}} - Ohcasey"></div>
                    </div>
                </div>

                <div class="card-info">
                    <h1 class="h1" v-if="!headline" v-cloak>Чехол для {{ $device_caption }} {{$product->name}}
                        (материал {{ $case_caption }})</h1>
                    <h1 class="h1 generated" v-if="headline" v-cloak>Чехол для @{{ headline.device }}
                        {{$product->name}} (материал @{{ headline.case }})</h1>

<div class="card-info__price">
                        @if (!empty($product->discount))
                            <div class="price price--old">{{$product->discount}}</div>
                        @endif
                        <span class="price">{{$product->price}}</span>
                    </div>

                    <div class="card-info__section" v-show="options.device" v-cloak>
                        <div class="card-info__label" v-if="options.device">@{{ options.device.name }}:</div>
                        <div class="card-info__body">
                            <div class="select">
                                <select class="js-select-item">
                                    <option v-for="v in devicelist" :value="v.id" :title="setModelTitle(v.title)">@{{v.title}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-info__section" v-show="options.color" v-cloak>
                        <div class="card-info__label" v-if="options.color">@{{ options.color.name }}:</div>
                        <div class="card-info__body">
                            <ul class="colors">
                                <li class="colors__item" v-for="v in colorlist" @click="selectOption('color', v.id)">
                                    <a class="colors__link colors__link js-color"
                                       :class="[selected.color == v.id ? 'is-active': '', v.title == '#FFFFFF' ? 'colors__link--white' : '']"
                                       :style="'background-color: ' + v.value" :title="setColorTitle(v.title)"></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card-info__section" v-show="options.case" v-cloak>
                        <div class="card-info__label" v-if="options.case">@{{ options.case.name }}:</div>
                        <div class="card-info__body">
                            <ul class="cover">
                                <li class="cover__item" v-for="v in caselist"
                                    @click="selectOption('case', v.id); changeHeadline('case', v.id)">
                                    <a class="cover__link js-cover" :class="selected.case == v.id ? 'is-active': ''" :title="setMaterialTitle(v.title)">
                                        <span class="cover__info">
                                            <span class="cover__title">@{{v.title}}</span>
                                            <span class="cover__text">@{{v.description}}</span>
                                        </span>
                                        <span class="cover__img"><img :src="v.image" alt=""></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                <!--{{-- <div class="card-info__section card-info__section--add">
                        <div class="card-info__label">Надпись на чехле:</div>
                        <div class="card-info__body">
                            <a href="{{route('home')}}">Добавить в конструкторе &rarr;</a>
                        </div>
                    </div> --}}-->
					<div class="card-info__section card-info__section--add">
                        <div class="card-info__label">Дополнительно:</div>
                        <div class="card-info__body">
                            <a href="#" id="link-add-text" onClick="yaCounter32242774.reachGoal('add_custom'); ga('send', 'event', 'Click', 'add_custom');">+ Добавить свою надпись</a>
                        </div>
                    </div> 

                    <div style="background-color: grey; padding: 20px; color: white;" v-if="optionsError" v-cloak>
                        Данного товара нет в наличии. Свяжитесь с нами по телефону:<br/> +7 (965) 396-97-85
                    </div>

                    <div v-if="!optionsError" class="card-info__section card-info__section--btn" s v-cloak>
                        <div v-if="!inCart">
                            <div class="btn" @click="addToCart()" id="addtocartbutton">ДОБАВИТЬ В КОРЗИНУ</div>
                        </div>
                        <div v-else>
                            <div class="card-info__warning">Товар в корзине!</div>
                            <a class="btn" href="{{url('/cart')}}" id="gotocart">ОФОРМИТЬ ЗАКАЗ</a>
                        </div>
                    </div>

                    <div class="card-info__section card-info__section--text">
                        <div class="card-info__title">Описание</div>
                        <div class="card-info__text">
							<?php if( strlen($product->description) < 50): ?>
								<p>Отличный чехол для IPhone из плотного силикона или бархатистого пластика с авторской
									иллюстрацией. Уникальная технология рельефной печати - абсолютный эффект ручной
									росписи.</p>
								<p>Гарантируем трогательный восторг.</p>
							<?php else: ?>
								<?php echo htmlspecialchars_decode($product->description); ?>
							<?php endif; ?>
							
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

            @if(isset($product->htmlDescription))
                {!! $product->htmlDescription !!}
            @endif
            <!--<div class="promo-block" v-if="caseType === 'silicone'" v-cloak>
                <div class="promo-block__img"><img src="/img/layout/img2.png" alt=""></div>

                <div class="promo-block__info">
                    <div class="h1">Силиконовый чехол</div>
                    <p class="promo-block__text">Благодаря сочетанию силиконовых бортиков и прозрачного пластика чехол
                        не растягивается со временем. Отлично защищает при падениях, не добавляет значительно объема
                        телефону и не скользит в руке.</p>
                </div>
            </div>

            <ul class="promo-list" v-if="caseType === 'silicone'" v-cloak>
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
            </ul>
        </div>

        <div class="banner banner--change-bg" v-if="caseType === 'silicone'"
             style="background-image: url('/img/layout/bg-paint.png')" v-cloak>
            <div class="banner__small" style="background-image: url('/img/layout/bg-paint-small.png')"></div>
            <div class="container">
                <div class="banner__inner">
                    <div class="banner__title h1">Эффект ручной росписи</div>
                    <div class="banner__text">Уникальная технология выпуклой печати создает потрясающий эффект ручной
                        росписи чехла красками. Изображения на чехлах рельефны на ощупь, яркие и стойкие
                    </div>
                </div>
            </div>
        </div>-->
    </div>
@endsection

@push('js')
<script>
    window.lroutes = {!! json_encode(getRoutes()) !!}
        window.product_id = {{$product->id}}
        window.product_name = "{{$product->name}}"
        window.offers = {!! json_encode($offers) !!}
        window.options = {!! json_encode($options) !!}
        window.product_photos = {!! json_encode($product->photos->toArray()) !!}
        window.bgName = "{{$product->background->name}}"
        window.oldvalues = {
            device: {!! json_encode($devices) !!},
            color: {!! json_encode($colors) !!},
            case: {!! json_encode($cases) !!}
        }
        window.devices = {!! json_encode($devices) !!}
        window.cases_page = true;
</script>
<script src="/js/frontcommons.js"></script>
<script src="{{url('js/product_show.js')}}"></script>
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

@push('css')
<style>
    [v-cloak] {
        display: none
    }
</style>
@endpush


@push('js')
<script>
$(document).ready(function(){
	var category = $(".breadcrumbs__link").last().html();
	var variant = $(".colors__link.is-active").attr("title");
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
			'variant': variant
		   }]
		 }
	   }
	});
	
	var caseMaterial = $('.cover__link.is-active .cover__title').text();
		
	$("#addtocartbutton").click(function(){
		var category = $(".breadcrumbs__link").last().html();
		var variant = $(".colors__link.is-active").attr("title");
		var dimension1 = $(".select2-selection__rendered").html();
		var dimension2 = $(".cover__link.is-active").find(".cover__title").html();
		dataLayer.push({
		  'event': 'addToCart',
		  'ecommerce': {
			'currencyCode': 'RUB',
			'add': {
			  'products': [{
				'name': '{{$product->name}}',
				'id': '{{$product->id}}',
				'price': '{{$product->price}}',
				'brand': 'Brand',
				'category': category,
				'variant': variant,
				'quantity': 1,
				'dimension1': dimension1,
				'dimension2': dimension2
			   }]
			}
		  }
		});
		
		// яндекс коммерция. добавление товара в корзину
		/*dataLayer.push({
			"ecommerce": {
				"add": {
					"products": [
						{
							"id": {{$product->id}},
							"name": "Чехол для {{ $device_caption }} «{{$product->name}}» (материал {{ $case_caption }})",
							"price": {{$product->price}},
							"category": category,
						}
					]
				}
			}
		});*/
		dataLayer.push({
			"ecommerce": {
				"add": {
					"products": [
						{
							"id": {{ $product->id }},
							"name": "{{ $product->name }}",
							"price": {{ $product->price }},
							"category": "{{ $product->firstCategory()->name }}",
							"quantity": 1,
							"variant": caseMaterial
						}
					]
				}
			}
		});
	});
	
	
	// яндекс коммерция. просмотр товара
	/*dataLayer.push({
		"ecommerce": {
			"detail": {
				"products": [
					{
						"id": {{$product->id}},
						"name" : "Чехол для {{ $device_caption }} «{{$product->name}}» (материал {{ $case_caption }})",
						"price": {{$product->price}},
						"category": category,
					},
				]
			}
		}
	});*/
	dataLayer.push({
		"ecommerce": {
			"detail": {
				"products": [
					{
						"id": {{ $product->id }},
						"name" : "{{ $product->name }}",
						"price": {{$product->price}},
						"category": "{{ $product->firstCategory()->name }}",
						"variant" : caseMaterial
					}
				]
			}
		}
	});	
});	
</script>
	
@endpush