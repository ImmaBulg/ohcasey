@extends('site.control-panel._base')
@section('cp-title')
	Добавьте смайл
	{{--<span id="upload" class="btn btn-default btn-file btn-xs">--}}
		{{--<span class="fa fa-upload"></span> или загрузите свой--}}
	{{--</span>--}}
@endsection
@section('cp-content')
	<div class="control-panel-smile">
		<div class="category">
			@foreach ($category as $i => $cat)
				<label class="r-button">
					<input type="radio" {{ $i == 0 ? 'checked' : '' }} autocomplete="off" name="cat" value="{{ $cat->smile_group_name }}">
					<span class="link">{{ $cat->smile_group_name }}</span>
				</label>
			@endforeach
		</div>
		<div class="list ps">
		</div>
	</div>
@endsection
