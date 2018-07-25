@extends('site.layouts.master')
@section('title', 'Ohcasey | Конструктор чехлов | Доставка')
@section('header')
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0"/>
	<link rel="stylesheet" href="{{ url('css/bootstrap-select.css') }}">
	<link rel="stylesheet" href="{{ _el('css/delivery.css') }}">
	<script src="{{ url('js/bootstrap-select.js') }}"></script>
	<script src="{{ url('js/jquery.scrollTo.js') }}"></script>
@endsection
@section('footer')
	<script src="{{ _el('js/delivery.js') }}"></script>
@endsection
@section('content')
	<div class="container-fluid">

		<div class="loader_spin"
			 style="background: url({{ url('img/loader-spin.gif') }}) center no-repeat; width:100%; height:400px; position: absolute;"></div>

		<a class="row add-header" href="{{ url('custom') }}#bg">
			<span class="icon-plus"></span>&nbsp;&nbsp;&nbsp;&nbsp;СОЗДАТЬ ЧЕХОЛ
		</a>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="h2_new">Рассчитайте сроки и стоимость доставки в Ваш город</h2>
					<!--<select>
						<option>Самарская область</option>
						<option>Москва</option>
					</select>
					-->
				</div>
				<div class="col-md-12">
					<input type="text" class="city-input form-control" placeholder="Выберите город"
						   style="display: none;">
				</div>
				<div class="col-md-12">
					<div class="suggestions-list">

					</div>
				</div>

				<div class="col-md-12 text-center colons not-moscow" style="display: none;">

					<div class="col-sm-4 item_block sdec-point hidden-element">
						<div class="item active  delivery-method" delivery-name="sdec-point">
							<h2>Самовывоз из пункта выдачи</h2>
							<span class="text">
							<span class="glyphicon glyphicon-time"></span>
							от
							<strong>2</strong>
							дней
						</span>
							<span class="cost">290р.</span>
						</div>
					</div>
					<div class="col-sm-4 item_block courier_sdec hidden-element">
						<div class="item  delivery-method" delivery-name="sdec-courier">
							<h2>Курьером на дом</h2>
							<span class="text">
							<span class="glyphicon glyphicon-time"></span>
							C
							<strong>---</strong>
						</span>
							<span class="cost">---</span>
						</div>
					</div>
					<div class="col-sm-4 item_block russian_post">
						<div class="item  delivery-method" delivery-name="russian-post">
							<h2>Почта России</h2>
							<span class="text">
							<span class="glyphicon glyphicon-time"></span>
							от
							<strong>5</strong>
							дней
						</span>
							<span class="cost">300р.</span>
						</div>
					</div>

				</div>


				<div class="col-md-12 text-center colons moscow" style="display: none;">
					<?php /*<div class="col-sm-4 item_block">
						<div class="item active delivery-method" delivery-name="showroom">
							<h2>Самовывоз из шоурум</h2>
							<span class="text">
							<span class="glyphicon glyphicon-time"></span>
							от
							<strong>2</strong>
							дней
						</span>
							<span class="cost">0р.</span>
						</div>
					</div> */ ?>
					<div class="col-sm-4 item_block">
						<div class="item  delivery-method" delivery-name="courier-moscow">
							<h2>Курьером по Москве</h2>
							<span class="text">
							<span class="glyphicon glyphicon-time"></span>
							от
							<strong>3</strong>
							дней
						</span>
							<span class="cost">350р.</span>
						</div>
					</div>
					<div class="col-sm-4 item_block">
						<div class="item  delivery-method" delivery-name="sdec-point">
							<h2>Пункт самовывоза СДЭК</h2>
							<span class="text">
							<span class="glyphicon glyphicon-time"></span>
							от
							<strong>5</strong>
							дней
						</span>
							<span class="cost">290р.</span>
						</div>
					</div>
				</div>


				<div class="col-md-12 text-center colons zaglushki" style="display: none;">

					<div class="col-sm-4 item_block">
						<div class="placeholder ">
							<h2>Самовывоз</h2>
							<div class="self">
							</div>
						</div>
					</div>
					<div class="col-sm-4 item_block">
						<div class="placeholder ">
							<h2>Самовывоз</h2>
							<div class="courier">
							</div>
						</div>
					</div>
					<div class="col-sm-4 item_block">
						<div class="placeholder ">
							<h2>Самовывоз</h2>
							<div class="russianpost">
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12 delivery-description" delivery-description-to="zaglushki-description"
					 style="display: none;">

					<div class="col-md-10 text-left col-md-offset-1 text_form">
						<p>
							Начните вводить желаемый город доставки, Вам тут же будут предложены подходящие варианты.
							После
							выбора города, Вы увидите доступные способы доставки для Вашего города, а также сроки и
							стоимость.
						</p>
						<p>
							Выберите наиболее подходящий для Вас способ, нажав на окно-кнопку, и мы тут же расскажем об
							этом
							способе подробнее. Для самовывоза из пункта выдачи СДЭК Вам бужет предложено выбрать
							подходящий
							адрес пункта.
						</p>
						<p>
							После оформления заказа в течение 15 минут с Вами свяжется наш менеджер (с 11:00 до 21:00).
							При условии наличия товара, мы постараемся отправить Ваш заказ в этот же день.
						</p>
						<div class="col-md-10 text-left col-md-offset-1 border-top">
							<div class="col-md-10 text-left col-md-offset-1 text-level-2">
								<p>Мы высылаем заказы в другие города только по 100% предоплате.<br>При получении заказа
									никаких доплат не требуется.</p>
							</div>
						</div>

					</div>
				</div>

				<div class="col-md-12 delivery-description" delivery-description-to="showroom" style="display: none;">
					<div class="col-md-10 text-left col-md-offset-1 text_form">
						<p>
							После оформления заказа в течение 15 минут с Вами свяжется наш менеджер (с 11:00 до 21:00).
							<br/>При условии наличия товара, Вы можете забрать его в этот же день.
						</p>
						<p>
							Именные чехлы мы доставляем в офис ЧЕРЕЗ ДЕНЬ после заказа
						</p>
						<div class="col-md-10 text-left col-md-offset-1 border-top border-bottom">
							<div class="col-md-10 text-center col-md-offset-1 text-level-2">
								<p>
									Оплата возможна при получении заказа
								</p>
							</div>
						</div>
						<div class="col-md-12 text-left">
							<p>
								Наш адрес:
							</p>
							<p>
								г. Москва, ул Таганская д.24, м. Марксистская (2 минуты от метро)
								<br/>Двухэтажный желтый особняк расположен прямо на улице Таганская
								(во дворы сворачивать не нужно)
								<br/>Вход в здание под вывеской Агентство недвижимости “Простор”
								<br/>На пункте охраны скажите, что Вы идете в офис “Окейси”
								<br/>2 этаж — дверь сразу напротив лестницы
							</p>
							<p>
								На машине:
							</p>
							Ближайшая платная парковка на переулке Маяковского (в 20ти шагах от офиса)
							</p>
						</div>
					</div>
				</div>


				<div class="col-md-12 delivery-description" delivery-description-to="courier-moscow"
					 style="display: none;">
					<div class="col-md-10 text-left col-md-offset-1 text_form">
						<p>
							После оформления заказа в течение 15 минут с Вами свяжется наш менеджер (с 11:00 до 21:00).
						</p>
						<p>
							При условии наличия товара, мы постараемся привезти Ваш заказ в этот же или на следующий
							день.
						</p>
						Именные чехлы мы доставляем НЕ РАНЬШЕ, ЧЕМ ЧЕРЕЗ ДЕНЬ после заказа
						</p>
						<div class="col-md-10 text-left col-md-offset-1 border-top">
							<div class="col-md-10 text-center col-md-offset-1 text-level-2">
								<p>Оплата возможна при получении заказа</p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12 delivery-description" delivery-description-to="sdec-point" style="display: none;">
					<!--<span class="result_city"><span class="glyphicon glyphicon-map-marker"></span>Смотреть на карте</span>-->
					<div class="col-md-10 text-left col-md-offset-1 text_form">
						<p>
							После оформления заказа в течение 15 минут с Вами свяжется наш менеджер (с 11:00 до 21:00).
							При условии наличия товара, мы постараемся отправить Ваш заказ в этот же день.
						</p>
						<p>Именные чехлы мы высылаем НА СЛЕДУЮЩИЙ ДЕНЬ после заказа</p>
						<p>
							* По России мы высылаем заказы курьерской компанией СДЭК
							После отправления Вашего заказа Вам на e-mail придет уведомление с номером трек-кода
							для отслеживания статуса отправки на сайте www.edostavka.ru
						</p>
						<p>
							Когда Ваш заказ придет в выбранный Вами пункт выдачи, Вам придет SMS-уведомление.<br>
							* По выходным пункты выдачи не работают <br>
							* Получателю необходим паспорт
						</p>
						<div class="col-md-10 text-left col-md-offset-1 border-top">
							<div class="col-md-10 text-left col-md-offset-1 text-level-2">
								<p>Мы высылаем заказы в другие города только по 100% предоплате.<br>При получении заказа
									никаких доплат не требуется.</p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12 delivery-description" delivery-description-to="sdec-courier"
					 style="display: none;">
					<!--<span class="result_city"><span class="glyphicon glyphicon-map-marker"></span>Смотреть на карте</span>-->
					<div class="col-md-10 text-left col-md-offset-1 text_form">
						<p>
							После оформления заказа в течение 15 минут с Вами свяжется наш менеджер (с 11:00 до 21:00).
							При условии наличия товара, мы постараемся отправить Ваш заказ в этот же день.
						</p>
						<p>
							Именные чехлы мы высылаем ЧЕРЕЗ ДЕНЬ после заказа
						</p>
						<p>
							* По России мы высылаем заказы курьерской компанией СДЭК
							После отправления Вашего заказа Вам на e-mail придет уведомление с номером трек-кода
							для отслеживания статуса отправки на сайте www.edostavka.ru
						</p>
						Когда заказ придет в Ваш город, с Вами свяжется курьер и уточнит удобное для Вас время и адрес
						доставки<br/>
						* По выходным курьерская доставка не осуществляется<br/>
						* Получателю необходим паспорт
						</p>
						<div class="col-md-10 text-left col-md-offset-1 border-top">
							<div class="col-md-10 text-left col-md-offset-1 text-level-2">
								<p>Мы высылаем заказы в другие города только по 100% предоплате.</p>
								<p>При получении заказа никаких доплат не требуется.</p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12 delivery-description" delivery-description-to="russian-post"
					 style="display: none;">
					<!--<span class="result_city"><span class="glyphicon glyphicon-map-marker"></span>Смотреть на карте</span>-->
					<div class="col-md-10 text-left col-md-offset-1 text_form">
						<p>
							После оформления заказа в течение 15 минут с Вами свяжется наш менеджер (с 11:00 до 21:00).
							При условии наличия товара, мы постараемся отправить Ваш заказ в этот же день.
						</p>
						<p>
							Именные чехлы мы высылаем ЧЕРЕЗ ДЕНЬ после заказа
						</p>
						<p>
							* Мы высылаем заказы Почтой России первым классом. В среднем доставка в любой город
							России занимает 7-9 дней. Для получения заказа Вам будет необходимо прийти в отделение
							почтовой связи, индекс которого вы указали в заказе.
							<br/>
							* Получателю необходим паспорт
						</p>
						<div class="col-md-10 text-left col-md-offset-1 border-top">
							<div class="col-md-10 text-left col-md-offset-1 text-level-2">
								<p>Мы высылаем заказы в другие города только по 100% предоплате.</p>
								<p>При получении заказа никаких доплат не требуется.</p>
							</div>
						</div>
					</div>
				</div>


				<div class="col-md-12 sdec-point-list hidden-element">
					<div class="spin">Загрузка списка пунктов самовывоза...</div>
					<ul>

					</ul>
				</div>

			</div>

			<div class="row map-row hidden-element">
				<div class="col-xs-12">
					<div class="container_yandex">
						<div class="central_block">
							<div id="map" class="spin"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="row map-row-showroom hidden-element">
				<div class="col-xs-12">
					<div class="container_yandex">
						<div class="central_block">
							<div id="map-showroom" class="spin"></div>
						</div>
					</div>
				</div>
			</div>

			<div id="push"></div>
		</div>
	</div>
@endsection
