@extends('admin.layout.master')
@section('content')

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Генерация товарных предложений</h1>
		</div>
	</div>

	<div class="text-center">
		<button class="btn btn-warning js-generate-offers">Дополнить ТП на основе значений опций</button>

		<div class="sk-folding-cube">
			<div class="sk-cube1 sk-cube"></div>
			<div class="sk-cube2 sk-cube"></div>
			<div class="sk-cube4 sk-cube"></div>
			<div class="sk-cube3 sk-cube"></div>
		</div>

		<div>
			<span class="current-generate-product">0</span> / <span class="length-generate-product">0</span>
		</div>

		<div class="generate-results">
		</div>

		<textarea class="generate-log"></textarea>
	</div>
@endsection