@extends('admin.layout.master')

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Выгрузка доставок за период</h2>
		</div>
	</div>
	<div class="col-lg-2">
		<form action="{{ route('admin.periodExport') }}" method="post" name="">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="startDate" class="form-control">Дата начала периода</label>
				<input type="text" class="js-startDate-datepicker form-control" name="startDate" id="startDate">
			</div>
			<div class="form-group">
				<button class="btn btn-primary">Создать</button>
				<a href="{{ route('admin.order.list') }}" class="btn btn-default">Отмена</a>
			</div>
		</form>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Выгрузка заказов для СДЭК</h2>
		</div>
	</div>
	<div class="col-lg-2">
		<form action="{{ route('admin.execute') }}" method="post" name="">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="text_top">ID заказов(по 1 в строке)</label>
				<textarea class="form-control" name="ids" rows="3" placeholder="ID заказов">{{ old('ids') }}</textarea>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Создать</button>
				<a href="{{ route('admin.order.list') }}" class="btn btn-default">Отмена</a>
			</div>
		</form>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<h2 class="page-header">Выгрузка заказов для курьера</h2>
		</div>
	</div>
	<div class="col-lg-2">
		<form action="{{ route('admin.courierExport') }}" method="post" name="">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="text_top">ID заказов(по 1 в строке)</label>
				<textarea class="form-control" name="ids" rows="3" placeholder="ID заказов">{{ old('ids') }}</textarea>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Создать</button>
				<a href="{{ route('admin.order.list') }}" class="btn btn-default">Отмена</a>
			</div>
		</form>
	</div>

@endsection