@extends('site.layouts.master')
<?php
	$keywords = 'конструктор чехлов для телефонов онлайн, создать чехол для телефона онлайн, онлайн конструктор чехлов для iphone и samsung, конструктор чехлов для смартфонов, конструктор чехлов на айфон и самсунг';
	$array_keywords = explode(', ', $keywords);
	if (count($array_keywords) > 3) {
		$title = mb_substr(mb_strtoupper($array_keywords[1]), 0, 1) . mb_substr($array_keywords[1], 1) . ' - ' . $array_keywords[3];
	}
	$description = 'Конструктор чехлов для телефонов онлайн - iPhone и Samsung Galaxy. Создай уникальный чехол для своего iPhone и Samsung он-лайн ✔Ультра стильные чехлы: дизайнерские, именные, с фамилией, с надписями. Ohcasey - модные чехлы на конструкторе! Доставка по всей России. ✆ +7 (965) 396-97-85';
?>

@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)


@section('header')
	<meta name="viewport" content="width=device-width, initial-scale=0.4, maximum-scale=1.0, user-scalable=1"/>
	<script type="application/javascript" src="{{ url('js/js.cookie.js') }}"></script>
	<script type="application/javascript" src="{{ _el('js/help.js') }}"></script>

	@if(env('APP_ENV') == 'production')
	<script type="application/javascript" src="{{ _el('js/metrikaGoals.js') }}"></script>
	@endif

	<script type="application/javascript" src="{{ _el('js/helper.js') }}"></script>
	<script type="application/javascript" src="{{ _el('js/constructor.js') }}"></script>
	<link rel="stylesheet" href="{{ _el('css/main.css') }}">

	@if(env('APP_ENV') == 'production')
		<!-- Google Analytics Content Experiment code -->
		<script>function utmx_section(){}function utmx(){}(function(){var
					k='134627699-3',d=document,l=d.location,c=d.cookie;
				if(l.search.indexOf('utm_expid='+k)>0)return;
				function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
				indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
						length:j))}}}var x=f('utmx'),xx=f('utmxx'),h=l.hash;d.write(
						'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
								'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
						'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
						valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
						'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
		</script>
		{{-- функция вызывает в constructor.js --}}
		<script>window.googleAbTest = function () {utmx('url','A/B');}</script>
		<!-- End of Google Analytics Content Experiment code -->
	@endif

@endsection
@section('content')
<?php
	$menu = [
			['n' => 'device', 'c' => 'ДЕВАЙС'],
			['n' => 'casey', 'c' => 'ЧЕХОЛ', 'icon' => 'cover'],
			['n' => 'bg', 'c' => 'ФОН'],
			['n' => 'font', 'c' => 'ТЕКСТ', 'icon' => 'text'],
			['n' => 'smile', 'c' => 'СМАЙЛЫ'],
	];
?>
	<div id="header">
		<div id="header-menu">
			@foreach ($menu as $m)
				<div class="item" data-menu="{{ $m['n'] }}">
					<a title="{{ $m['c'] }}" href="{{ \Request::getUri() }}#{{ $m['n'] }}" data-name="{{ $m['n'] }}">
						<span class="icon icon-{{ array_get($m, 'icon', $m['n']) }}"></span>
						<span class="hidden-xs">{{ $m['c'] }}</span>
					</a>
				</div>
			@endforeach
			@if(!session('isAdminEdit', false))
				<div class="item cart">
					<a title="Корзина" href="{{ url('custom/cart') }}">
						<span class="icon icon-cart"></span>
						<span>{{ $cartCount }} <span class="hidden-xs">{{ \App\Helper::pluralize($cartCount, ['товар', 'товара', 'товаров']) }}</span></span>
						<span class="hidden-xs hidden-sm hidden-md"> в корзине</span>
					</a>
				</div>
			@endif
		</div>
		<div style="clear:both;"></div>
	</div>
	<div id="constructor">
		<div class="viewport ps">
			<div class="centerer">
				<div class="wrapper">
					<div class="ide center-block">
						<div class="clearfix">
							<div class="helper helper-left"></div>
							<div id="constructor-place"></div>
							<div class="helper helper-right"></div>
						</div>
						<div class="clearfix">
							<div class="helper helper-bottom center-block"></div>
						</div>
					</div>
				</div>
				<div id="help"></div>
			</div>
		</div>
		<div id="control-panel"></div>
		{{--<div id="control-order">--}}
			{{--<div id="control-next" class="link">ДАЛЕЕ <span class="icon-arrow-right"></span></div>--}}
			{{--<div id="make-order" class="link">В КОРЗИНУ <span class="icon-cart"></span></div>--}}
		{{--</div>--}}
	</div>
@endsection

@push('js')
@if(env('APP_ENV') == 'production')
    <script>
        $('body').on('click', '#make-order', function () {
			var cost = $(this).find('[data-cost]').data('cost');
            fbq('track', 'AddToCart', {
				contents: 'casey',
                content_type: 'product',
                value: cost,
                currency: 'RUB'
            });
        });
    </script>
@endif
@endpush