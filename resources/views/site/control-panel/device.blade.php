@extends('site.control-panel._base')
@section('cp-title', 'Выберите устройство')
@section('cp-content')
	<div class="control-panel-device">
		@foreach ($devices as $device)
			<div class="device list-item link"
				 data-device="{{ $device->device_name }}"
				 data-name="{{ $device->device_caption }}"
				 data-mask="{{ json_encode($device->mask) }}" >
				<div class="title">{{ $device->device_caption }}</div>
				<div class="icon"><img class="icon" src="{{ url('storage/device', [$device->device_name, $device->icon]) }}"></div>
			</div>
		@endforeach
	</div>
@endsection
