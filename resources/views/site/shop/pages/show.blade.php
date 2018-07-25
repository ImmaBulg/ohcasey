@extends('site.layouts.app')

@section('title', $page->title)
@section('keywords', $page->keywords)
@section('description', $page->description)

@section('content')
	<div class="container">
		<h1>{{$page->h1}}</h1>
	</div>
    {!! $page->content !!}
@endsection