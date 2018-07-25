@extends('site.control-panel._base')
@section('cp-title')
	Выберите фон
	{{--<span id="upload" class="btn btn-default btn-file btn-xs">--}}
		{{--<span class="fa fa-upload"></span> или загрузите свой--}}
	{{--</span>--}}
@endsection
@section('cp-content')
	<div class="control-panel-bg">
		<div class="category">
			@foreach ($categories as $i => $category)
				<label class="r-button">
					<input type="radio" {{ $i == 0 ? 'checked' : '' }} autocomplete="off" name="cat" value="{{ $category->name }}">
					<span class="link">{{ $category->name }}</span>
				</label>
			@endforeach
		</div>
		<div class="list ps">
		</div>
	</div>
@endsection
