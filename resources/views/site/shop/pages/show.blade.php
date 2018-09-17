@extends('site.layouts.app')

@section('title', $page->title)
@section('keywords', $page->keywords)
@section('description', $page->description)

@section('content')
	<div class="container">
		<h1>{{$page->h1}}</h1>
	</div>
	@if ($page->slug === 'catalog')
		<div style="padding-left: 4%;">
			@include('site.shop.partial.left-menu')
		</div>
	@endif
		{!! $page->content !!}
@endsection