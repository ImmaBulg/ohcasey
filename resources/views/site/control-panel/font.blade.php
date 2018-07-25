@extends('site.control-panel._base')
@section('cp-title', 'Добавьте текст')
@section('cp-content')
	<div class="control-panel-font">
		@foreach ($fonts as $font)
			<div class="font list-item link" data-font="{{ $font->font_name }}">
				<img src="{{ action('OhcaseyController@fontToImage', ['font' => $font->font_name, 'text' => $font->font_caption, 'color' => '#555']) }}">
			</div>
		@endforeach
	</div>
@endsection
