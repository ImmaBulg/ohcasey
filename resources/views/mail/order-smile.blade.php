<tr>
	<td style="padding:5px" colspan="2">Информация о смайле</td>
</tr>
<tr>
	<td style="padding:5px">Смайл</td>
	<td style="padding:5px">
		<a href="{{ url(isset($type) && $type == 'user' ? 'storage/upload' : 'storage/smile', [$name]) }}" target="_blank">{{ url(isset($type) && $type == 'user' ? 'storage/upload' : 'storage/smile', [$name]) }}</a>
	</td>
</tr>
{{--<tr>--}}
	{{--<td style="padding:5px">Координата X</td>--}}
	{{--<td style="padding:5px">{{ $left }}</td>--}}
{{--</tr>--}}
{{--<tr>--}}
	{{--<td style="padding:5px">Кордината Y</td>--}}
	{{--<td style="padding:5px">{{ $top }}</td>--}}
{{--</tr>--}}
{{--<tr>--}}
	{{--<td style="padding:5px">Ширина</td>--}}
	{{--<td style="padding:5px">{{ $width }}</td>--}}
{{--</tr>--}}
{{--<tr>--}}
	{{--<td style="padding:5px">Высота</td>--}}
	{{--<td style="padding:5px">{{ $height }}</td>--}}
{{--</tr>--}}
<tr>
	<td style="padding:5px">Угол поворота</td>
	<td style="padding:5px">{{ $angle }}</td>
</tr>
