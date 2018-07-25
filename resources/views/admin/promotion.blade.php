@extends('admin.layout.master')
@section('content')
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Промокоды</h1>
		</div>
		<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->

	{{-- ALERT --}}
	<div class="flash-message">
		@foreach (['danger', 'warning', 'success', 'info'] as $msg)
			@if(Session::has('alert-' . $msg))
				<p class="alert alert-{{ $msg }} alert-dismissable fade in">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
			@endif
		@endforeach
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div id="modal" class="modal fade" tabindex="-1" role="dialog">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title">Добавить/Редактировать промокод</h4>
						</div>
						<div class="modal-body">
							<form action="" method="post" id="form" class="form-horizontal">
								<div id="form_additional"></div>
								{{ csrf_field() }}

								<div class="form-group">
									<label for="code_name" class="col-sm-3 control-label">Название *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control chk-mandatory" id="code_name" name="code_name" placeholder="Скидка 1">
									</div>
								</div>

								<div class="form-group">
									<label for="code_value" class="col-sm-3 control-label">Код *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control chk-mandatory" id="code_value" name="code_value" placeholder="CODE123">
									</div>
								</div>

								<div class="form-group">
									<label for="code_enabled" class="col-sm-3 control-label">Активен</label>
									<div class="col-sm-9">
										<input type="checkbox" checked data-toggle="toggle" data-width="60" data-height="32" data-on="Да" data-off="Нет" id="code_enabled" name="code_enabled">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Тип скидки</label>
									<div class="col-sm-9">
										<div class="btn-group" data-toggle="buttons">
											<label class="btn btn-primary active">
												<input type="radio" name="code_discount_unit" autocomplete="off" value="%" checked> Процент
											</label>
											<label class="btn btn-primary">
												<input type="radio" name="code_discount_unit" autocomplete="off" value=""> Сумма
											</label>
											<label class="btn btn-primary">
												<input type="radio" name="code_discount_unit" autocomplete="off" value="D"> Доставка
											</label>
										</div>
									</div>
								</div>

								<div class="form-group" id="code_discount_value_group">
									<label for="code_discount_value" class="col-sm-3 control-label">Дискаунт *</label>
									<div class="col-sm-9">
										<input type="text" class="form-control chk-mandatory chk-int"
											   data-chck-dep="r.code_discount_unit == '%' || r.code_discount_unit == ''"
											   id="code_discount_value" name="code_discount_value" placeholder="10">
									</div>
								</div>

								<div class="form-group">
									<label for="code_valid_from" class="col-sm-3 control-label">Действует с</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="code_valid_from" name="code_valid_from">
									</div>
								</div>

								<div class="form-group">
									<label for="code_valid_till" class="col-sm-3 control-label">Действует по</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" id="code_valid_till" name="code_valid_till">
									</div>
								</div>

								<div class="form-group">
									<label for="code_cond_cart_count" class="col-sm-3 control-label">Размер корзины</label>
									<div class="col-sm-9">
										<input type="text" class="form-control chk-int" id="code_cond_cart_count" name="code_cond_cart_count" placeholder="Размер">
									</div>
								</div>

								<div class="form-group">
									<label for="code_cond_cart_amount" class="col-sm-3 control-label">Размер корзины</label>
									<div class="col-sm-9">
										<input type="text" class="form-control chk-int" id="code_cond_cart_amount" name="code_cond_cart_amount" placeholder="Сумма">
									</div>
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
							<button type="button" class="btn btn-primary" id="modal_apply">Сохранить</button>
						</div>
					</div><!-- /.modal-content -->
				</div><!-- /.modal-dialog -->
			</div><!-- /.modal -->

			<div class="panel panel-default">
				<div class="panel-heading clearfix">
					<i class="fa fa-table fa-fw"></i> Список промокодов <button class="btn btn-default m-l-15 btn-sm" id="add">Добавить <span class="fa fa-plus"></span></button>
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-hover table-condensed">
							<thead>
							<tr>
								<th class="text-right" width="100">#</th>
								<th width="200">Имя</th>
								<th width="150">Код</th>
								<th width="100" class="text-center">Активен</th>
								<th width="100" class="text-right">Использован</th>
								<th width="100" class="text-right">Дискаунт</th>
								<th width="150">Действует с</th>
								<th width="150">Действует по</th>
								<th width="100" class="text-right">Размер корзины</th>
								<th width="100" class="text-right">Сумма корзины</th>
								<th width="50" class="text-center"><span class="fa fa-edit"></span></th>
								<th width="50" class="text-center"><span class="fa fa-remove"></span></th>
							</tr>
							</thead>
							<tbody>
							<tr>
								<td><form class="form-filter"><input autocomplete="off" name="f_code_id" value="{{ request('f_code_id', '') }}" type="text" class="form-control text-right" placeholder="#"></form></td>
								<td><form class="form-filter"><input autocomplete="off" name="f_code_name" value="{{ request('f_code_name', '') }}" type="text" class="form-control" placeholder="Имя"></form></td>
								<td><form class="form-filter"><input autocomplete="off" name="f_code_value" value="{{ request('f_code_value', '') }}" type="text" class="form-control" placeholder="Код"></form></td>
								<td><form class="form-filter"><input autocomplete="off" name="f_code_enabled" value="{{ request('f_code_enabled', '') }}" type="text" class="form-control text-center" placeholder="Да/Нет"></form></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							</tbody>
							<tbody>
							@forelse($codes as $code)
								<tr>
									<td class="text-right">{{ $code->code_id }}</td>
									<td>{{ $code->code_name }}</td>
									<td>{{ $code->code_value }}</td>
									<td class="text-center">{{ $code->code_enabled ? 'Да' : '' }}</td>
									<td class="text-right">{{ \App\Models\Cart::where('promotion_code_id', '=', $code->code_id)->count() }}</td>
									<td class="text-right">{{ $code->code_discount }}</td>
									<td>{{ $code->code_valid_from }}</td>
									<td>{{ $code->code_valid_till }}</td>
									<td class="text-right">{{ $code->code_cond_cart_count }}</td>
									<td class="text-right">{{ $code->code_cond_cart_amount }}</td>
									<td class="text-center"><span class="fa fa-edit link promotion-edit" data-row="{{ $code }}"></span></td>
									<td class="text-center">
										<span class="fa fa-remove link promotion-remove"></span>
										<form method="post" action="{{ url('admin/promotion', [$code->code_id]) }}">
											<input type="hidden" name="_method" value="DELETE">
										</form>
									</td>
								</tr>
							@empty
								<tr>
									<td colspan="100%" class="text-center bg-warning"><strong>Ничего нет</strong></td>
								</tr>
							@endforelse
							</tbody>
						</table>
					</div>
					{{ $codes->links() }}
				</div>
				<!-- /.panel-body -->
			</div>
			<!-- /.panel -->
		</div>
	</div>
@endsection
@section('scripts')
	<script src="{{ url('js/admin-promotion.js')}}"></script>
	<script src="{{ url('js/bootstrap2-toggle.js')}}"></script>
	<link href="{{ url('css/bootstrap2-toggle.css') }}" rel="stylesheet">
@endsection
