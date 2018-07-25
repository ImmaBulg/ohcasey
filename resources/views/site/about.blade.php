@extends('site.layouts.master')
@section('title', 'Ohcasey | Конструктор чехлов | О нас')
@section('header')
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0"/>
	<link rel="stylesheet" href="{{ url('css/bootstrap-select.css') }}">
	<link rel="stylesheet" href="{{ _el('css/about.css') }}">
	<script src="{{ url('js/bootstrap-select.js') }}"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery.waitforimages/1.5.0/jquery.waitforimages.min.js"></script>
@endsection
@section('footer')
	<script src="{{ _el('js/about.js') }}"></script>
@endsection
@section('content')
	<div class="container-fluid">

		<a class="row add-header" href="{{ url('custom') }}#bg">
			<span class="icon-plus"></span>&nbsp;&nbsp;&nbsp;&nbsp;СОЗДАТЬ ЧЕХОЛ
		</a>

		<div class="container">
			<div class="row">
				<div class="col-md-12 header_div">
					<h2 class="h2_new">Узнайте больше о наших чехлах</h2>
					<div class="col-sm-4 select_cases" data-block="0">
						<div>
							Силикон
						</div>
					</div>
					<div class="col-sm-4 select_cases" data-block="1">
						<div>
							МАТОВЫЙ ПЛАСТИК
						</div>

					</div>
					<div class="col-sm-4 select_cases" data-block="2">
						<div>
							ЧЕРНЫЙ ПЛАСТИК
						</div>

					</div>
				</div>

				<div class="col-sm-12 margin_top_40 sliders_block">

					<div class="body_block body_block_0 col-sm-12">
						<!-- значение data-block -->
						<div class="col-md-offset-1 col-sm-offset-1 col-sm-6 col-xs-10 col-xs-offset-1 col-md-6 slider_cases">
							<div id="Carousel" class="carousel slide carousel-fade carousel-slider">
								<div class="carousel-inner">
									<div style="background-image: url({{ url('img/about/cases_1/cases-074.png') }});" class="item  active"><!-- gold 5-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_1/cases-099.png') }});" class="item"><!-- silver 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_2/cases-295.png') }});" class="item"><!-- gold 5-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_2/cases-278.png') }});" class="item"><!-- silver 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_3/cases-296.png') }});" class="item"><!-- gold 5-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_3/cases-282.png') }});" class="item"><!-- silver 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_5/cases-387.png') }});" class="item"><!-- gold 5 -->
									</div>
									<div style="background-image: url({{ url('img/about/cases_5/cases-395.png') }});" class="item"><!-- silver 6 -->
									</div>
									<a data-slide="prev" href="#Carousel" class="left carousel-control">
									</a>
									<a data-slide="next" href="#Carousel" class="right carousel-control">
									</a>
								</div>
							</div>
						</div>
						<div class="col-sm-5 col-md-5 text-left text_cases_left">
							<h2>Прозрачный силикон</h2>
							<p>
								Тонкий пластиковый чехол с силиконовыми бортиками. Не растянется со временем.
								Закрывает Iphone со всех сторон. Есть заглушки для технических отверстий.
								Отлично защищает при падениях.
							</p>
							<h3>Устройства:</h3>
							<p>
								Iphone 6s / iPhone 6s Plus / iPhone 6 / iPhone 6 Plus / iPhone  5s / iPhone 5 / iPhone 5 с / iPhone  4s
							</p>
							<a title="Создать чехол" href="{{ url('/custom') }}" class="btn_ohcasey">Создать чехол</a>
						</div>
					</div>

					<div class="body_block body_block_1 col-sm-12">
						<!-- значение data-block -->
						<div class="col-md-offset-1 col-sm-offset-1 col-sm-6 col-xs-10 col-xs-offset-1 col-md-6 slider_cases">
							<div id="Carousel2" class="carousel slide carousel-fade carousel-slider">
								<div class="carousel-inner">
									<div style="background-image: url({{ url('img/about/cases_1/cases-100.png') }});" class="item  active"><!-- silver 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_2/cases-284.png') }});" class="item"><!-- silver 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_2/cases-299.png') }});" class="item"><!-- gold 5-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_3/cases-285.png') }});" class="item"><!-- silver 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_3/cases-298.png') }});" class="item"><!-- gold 5-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_5/cases-388.png') }});" class="item"><!-- gold 5 -->
									</div>
									<div style="background-image: url({{ url('img/about/cases_5/cases-396.png') }});" class="item"><!-- silver 6 -->
									</div>
									<a data-slide="prev" href="#Carousel2" class="left carousel-control">
									</a>
									<a data-slide="next" href="#Carousel2" class="right carousel-control">
									</a>
								</div>

							</div>
						</div>
						<div class="col-sm-4 text-left text_cases_left">
							<h2>МАТОВЫЙ ПЛАСТИК	</h2>
							<p>
								Тонкий полупрозрачный матовый чехол. Идеально садится на iPhone.
								Приятная бархатистая поверхность. Практически не утолщает iPhone.
							</p>
							<h3>Устройства:</h3>
							<p>
								Iphone 6s / iPhone 6s Plus / iPhone 6 / iPhone 6 Plus / iPhone  5s / iPhone 5 /
							</p>
							<a title="Создать чехол" href="{{ url('/custom') }}" class="btn_ohcasey">Создать чехол</a>
						</div>
					</div>

					<div class="body_block body_block_2 col-sm-12">
						<!-- значение data-block -->
						<div class="col-md-offset-1 col-sm-offset-1 col-sm-6 col-xs-10 col-xs-offset-1 col-md-6 slider_cases">
							<div id="Carousel3" class="carousel slide carousel-fade carousel-slider">
								<div class="carousel-inner">
									<div style="background-image: url({{ url('img/about/cases_1/cases-087.png') }});" class="item  active"><!-- black silver 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_1/cases-069.png') }});" class="item"><!-- black 5-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_2/cases-308.png') }});" class="item"><!-- black 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_2/cases-300.png') }});" class="item"><!-- black 5-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_3/cases-309.png') }});" class="item"><!-- black 6-->
									</div>
									<div style="background-image: url({{ url('img/about/cases_3/cases-301.png') }});" class="item"><!-- black 5 -->
									</div>
									<div style="background-image: url({{ url('img/about/cases_5/cases-399.png') }});" class="item"><!-- black 6 -->
									</div>
									<div style="background-image: url({{ url('img/about/cases_5/cases-392.png') }});" class="item"><!-- black 5 -->
									</div>
									<a data-slide="prev" href="#Carousel3" class="left carousel-control">
									</a>
									<a data-slide="next" href="#Carousel3" class="right carousel-control">
									</a>
								</div>
							</div>
						</div>
						<div class="col-md-4 text-left text_cases_left">
							<h2>ЧЕРНЫЙ ПЛАСТИК</h2>
							<p>
								Тонкий черный матовый чехол. Идеально садится на iPhone.
								Приятная бархатистая поверхность. Практически не утолщает iPhone.
							</p>
							<h3>Устройства:</h3>
							<p>
								Iphone 6s / iPhone 6s Plus / iPhone 6 / iPhone 6 Plus / iPhone  5s / iPhone 5 / iPhone 5 с / iPhone  4s</p>
							<a title="Создать чехол" href="{{ url('/custom') }}" class="btn_ohcasey">Создать чехол</a>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
@endsection
