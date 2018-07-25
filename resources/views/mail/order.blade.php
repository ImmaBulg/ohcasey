<html>
	<body>

		{{-- COMMON --}}
		<h1 style="font-size:22px;padding-bottom:10px">Информация о заказе</h1>
		<table style="text-align:center;font-size:12px;border-collapse: collapse; width: 800px;" border="1" cellpadding="0"
			   cellspacing="0">
			<tbody>
				@if(isset($printLink))
					<tr>
						<td style="padding:5px" width="50%">Версия для печати</td>
						<td style="padding:5px"><a href="{{ url($printLink) }}" target="_blank">Ссылка</a></td>
					</tr>
				@endif
				<tr>
					<td style="padding:5px" width="50%">Номер заказа</td>
					<td style="padding:5px;font-weight: bold">{{ $order->order_id }}</td>
				</tr>
				<tr>
					<td style="padding:5px" width="50%">Время заказа</td>
					<td style="padding:5px">{{ $order->order_ts }}</td>
				</tr>
				@if($order->client_name)
					<tr>
						<td style="padding:5px" width="50%">ФИО</td>
						<td style="padding:5px">{{ $order->client_name }}</td>
					</tr>
				@endif
				@if($order->client_email)
					<tr>
						<td style="padding:5px" width="50%">E-mail</td>
						<td style="padding:5px">
							<a href="mailto:{{ $order->client_email }}" target="_blank">{{ $order->client_email }}</a></td>
					</tr>
				@endif
				@if($order->utm)
					<tr>
						<td style="padding:5px" width="50%">UTM</td>
						<td style="padding:5px">{{ $order->utm }}</td>
					</tr>
				@endif
				<tr>
					<td style="padding:5px" width="50%">Телефон</td>
					<td style="padding:5px">
						<a href="tel:{{ $order->client_phone }}" target="_blank">{{ $order->client_phone }}</a>
					</td>
				</tr>
				@if($order->delivery_name)
					<tr>
						<td style="padding:5px" width="50%">Доставка</td>
						<td style="padding:5px">{{ $order->delivery->delivery_caption }}</td>
					</tr>
				@endif
				@if($order->country_iso)
					<tr>
						<td style="padding:5px" width="50%">Страна</td>
						<td style="padding:5px">{{ $order->country->country_name_ru }}</td>
					</tr>
				@endif
				@if($order->city)
					<tr>
						<td style="padding:5px" width="50%">Город</td>
						<td style="padding:5px">{{ $order->city->city_name_full }}</td>
					</tr>
				@endif
				@if($order->delivery_name == 'courier' || $order->delivery_name == 'pickpoint')
					@if($order->delivery_name == 'pickpoint')
						<tr>
							<td style="padding:5px" width="50%">Пункт выдачи</td>
							<td style="padding:5px">{{ $order->deliveryCdek->cdek_pvz.' | '.$order->deliveryCdek->cdek_pvz_name }}</td>
						</tr>
						<tr>
							<td style="padding:5px" width="50%">Адрес</td>
							<td style="padding:5px">{{ $order->deliveryCdek->cdek_pvz_address }}</td>
						</tr>
						<tr>
							<td style="padding:5px" width="50%">Режим работы</td>
							<td style="padding:5px">{{ $order->deliveryCdek->cdek_pvz_worktime }}</td>
						</tr>
					@endif
				@elseif($order->delivery_name == 'post')
					<tr>
						<td style="padding:5px" width="50%">Индекс</td>
						<td style="padding:5px">{{ $order->deliveryRussianPost->post_code }}</td>
					</tr>
				@endif
				@if(!empty($order->delivery_address))
					<tr>
						<td style="padding:5px" width="50%">Адрес</td>
						<td style="padding:5px">{{ $order->delivery_address }}</td>
					</tr>
				@endif
				@if(!empty($order->order_comment))
					<tr>
						<td style="padding:5px" width="50%">Коментарии к заказу</td>
						<td style="padding:5px">{{ $order->order_comment }}</td>
					</tr>
				@endif
				@if(!empty($order->delivery_date))
					<tr>
						<td style="padding:5px" width="50%">Дата доставки</td>
						<td style="padding:5px">{{ $order->delivery_date }}</td>
					</tr>
				@endif
				<tr>
					<td style="padding:5px" width="50%">Вид оплаты</td>
					<td style="padding:5px">Наличными/Картой</td>
				</tr>
				<tr>
					<td style="padding:5px" width="50%">Стоимость</td>
					<td style="padding:5px">{{ $order->order_amount + $order->getSpecialItemsSum() }}</td>
				</tr>
				@if($order->delivery_amount)
					<tr>
						<td style="padding:5px" width="50%">Стоимость за доставку</td>
						<td style="padding:5px">{{ $order->delivery_amount }}</td>
					</tr>
				@endif
				@if($order->discount_amount)
					<tr>
						<td style="padding:5px" width="50%">Скидка</td>
						<td style="padding:5px">{{ -$order->discount_amount }}</td>
					</tr>
				@endif
				<tr>
					<td style="padding:5px" width="50%">Общая стоимость</td>
					<td style="padding:5px">{{ $order->getTotalSum() }}</td>
				</tr>

			</tbody>
		</table>

		@if ($order->cart)
			@if(isset($full) && $full)
				<h2>Информация об элементах</h2>
				@foreach($order->cart->cartSetCase as $n => $item)
					<h3>Элемент {{ $n + 1 }}</h3>
					<table style="text-align:center;font-size:12px"
						   border="0" cellpadding="0" cellspacing="0"
						   width="100%">
						<tbody>
						<tr>
							<td style="vertical-align: top" width="60%">
								<table style="text-align:center;font-size:12px;border-collapse: collapse"
									   border="1" cellpadding="0"
									   cellspacing="0" width="100%">
									<tbody>
									@include('mail.order-device', ['item' => $item, 'order' => $order])
									@foreach(array_get($item->item_source, 'SMILE', []) as $smile)
										@if(!array_get($smile, 'hidden'))
											@include('mail.order-smile', $smile)
										@endif
									@endforeach
									@foreach(array_get($item->item_source, 'TEXT', []) as $text)
										@if(!array_get($text, 'hidden'))
											@include('mail.order-font', $text)
										@endif
									@endforeach
									</tbody>
								</table>
							</td>
							<td>
								<img src="{{ route('orders.showImage', ['order' => $order, 'hash' => $order->order_hash, 'img' => 'item_'.$item->cart_set_id.'.png']) }}"
										alt="Элемент {{ $n + 1 }}" height="auto"
										width="auto">
							</td>
						</tr>
						</tbody>
					</table>
				@endforeach

				@foreach($order->cart->cartSetProducts as $n => $item)
					<h3>Элемент {{ $n + 1 }}</h3>
					<table style="text-align:center;font-size:12px"
						   border="0" cellpadding="0" cellspacing="0"
						   width="100%">
						<tbody>
						<tr>
							<td style="vertical-align: top" width="60%">
								<table style="text-align:center;font-size:12px;border-collapse: collapse"
									   border="1" cellpadding="0"
									   cellspacing="0" width="100%">
									<tbody>
									<tr>
										<td style="padding:5px">
											Товар
										</td>
										<td>{{ $item->offer->product->name }}</td>
									</tr>
									@if (is_array($item->offer->option_values))
									<tr>
										<td style="padding:5px">
											Свойства
										</td>
										<td>{{ $item->offer->option_values->implode(', ') }}</td>
									</tr>
									@endif
									</tbody>
								</table>
							</td>
							<td>
								<img src="{{ url($item->offer->product->mainPhoto()) }}"
										alt="Элемент {{ $n + 1 }}" height="auto"
										width="240px" style="height: auto; width: 240px">
							</td>
						</tr>
						</tbody>
					</table>
				@endforeach

				{{-- USER INFO --}}
				<h2>Информация о пользователе</h2>
				<table style="text-align:center;font-size:12px;border-collapse: collapse" border="1"
					   cellpadding="0" cellspacing="0" width="100%">
					<tbody>
					<tr>
						<td style="padding:5px" width="50%">Операционная система
						</td>
						<td style="padding:5px">{{ $os }}</td>
					</tr>
					<tr>
						<td style="padding:5px" width="50%">Браузер</td>
						<td style="padding:5px">{{ $browser }}</td>
					</tr>
					<tr>
						<td style="padding:5px" width="50%">Версия</td>
						<td style="padding:5px">{{ $version }}</td>
					</tr>
					<tr>
						<td style="padding:5px" width="50%">Полный user-agent
						</td>
						<td style="padding:5px">{{ $order->cart->cart_user_agent }}</td>
					</tr>
					</tbody>
				</table>
			@else
				@foreach($order->cart->cartSetCase as $n => $item)
					<div style="display: inline-block; margin-right: 0px; margin-top: 20px; text-align: center">
						<div>{{ $item->device->device_caption }}</div>
						<div>{{ $item->casey->case_name }}</div>
						<img width="200"
								src="{{ route('orders.showImage', ['order' => $order, 'has' => $order->order_hash, 'img' => 'item_'.$item->cart_set_id.'.png']) }}"
								alt="Чехол">
					</div>
				@endforeach
			@endif
		@endif
	</body>
</html>

