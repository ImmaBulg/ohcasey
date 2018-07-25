<tr>
	<td style="padding:5px" colspan="2">
		Информация о чехле
	</td>
</tr>
<tr>
	<td style="padding:5px">
		Устройство
	</td>
	<td>{{ $item->device->device_caption }}</td>
</tr>
@if(isset($colorHex))
	<tr>
		<td style="padding:5px">Цвет устройства
		</td>
		<td style="background-color:{{ $colorHex }}"></td>
	</tr>
@endif
<tr>
	<td style="padding:5px">Название чехла</td>
	<td>{{ $item->casey->case_caption }}</td>
</tr>
<tr>
	<td style="padding:5px">
		Ссылка на изображение
	</td>
	<td style="padding:5px">
		<a href="{{ route('orders.showImage', ['order' => $order, 'hash' => $order->order_hash, 'img' => 'item_'.$item->cart_set_id.'.png']) }}" target="_blank">{{ url('order', [$order->order_id, $order->order_hash, 'item_'.$item->cart_set_id.'.png']) }}</a>
	</td>
</tr>
@if($item->item_source['DEVICE']['bg'])
	<tr>
		<td style="padding:5px">
			Фон чехла
		</td>
		<td style="padding:5px">
			<a href="{{ url(array_get($item->item_source, 'DEVICE.type') == 'user' ? 'storage/upload' : 'storage/bg', [$item->item_source['DEVICE']['bg']]) }}" target="_blank">{{ url(array_get($item->item_source, 'DEVICE.type') == 'user' ? 'storage/upload' : 'storage/bg', [$item->item_source['DEVICE']['bg']]) }}</a>
		</td>
	</tr>
@endif
<tr>
	<td style="padding:5px">
		Стоимость
	</td>
	<td style="padding:5px">
		{{ $item->item_cost }}
	</td>
</tr>
