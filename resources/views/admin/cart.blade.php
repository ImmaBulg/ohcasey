@extends('admin.layout.master')
@section('content')
	<form action="{{ url('admin/cart') }}" method="get">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Корзины</h1>
			</div>
			<!-- /.col-lg-12 -->
		</div>
		<!-- /.row -->

		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-calendar fa-fw"></i> Интервал
					</div>
					<div class="panel-body clearfix">
						<div class="btn-group m-r-15 pull-left" role="group">
							<button type="button" class="btn btn-default btn-sm btn-date" data-from="" data-to="">Все время</button>
							<button type="button" class="btn btn-default btn-sm btn-date"
									data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}"
									data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}">Сегодня</button>
							<button type="button" class="btn btn-default btn-sm btn-date"
									data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subDay()->format('Y-m-d') }}"
									data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subDay()->format('Y-m-d') }}">Вчера</button>
							<button type="button" class="btn btn-default btn-sm btn-date"
									data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subDay(7)->format('Y-m-d') }}"
									data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}">7 дней</button>
							<button type="button" class="btn btn-default btn-sm btn-date"
									data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subDay(14)->format('Y-m-d') }}"
									data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}">14 дней</button>
							<button type="button" class="btn btn-default btn-sm btn-date"
									data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subDay(30)->format('Y-m-d') }}"
									data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}">30 дней</button>
							<button type="button" class="btn btn-default btn-sm btn-date"
									data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->firstOfMonth()->format('Y-m-d') }}"
									data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}">Этот месяц</button>
							<button type="button" class="btn btn-default btn-sm btn-date"
									data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subMonth(1)->firstOfMonth()->format('Y-m-d') }}"
									data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subMonth(1)->endOfMonth()->format('Y-m-d') }}">Прошлый месяц</button>
						</div>
						<div class="input-daterange input-group pull-left m-r-15" id="report-range">
							<input autocomplete="off" type="text" class="form-control input-sm" name="f_date_start" id="f_date_start" value="{{ request('f_date_start', '') }}"/>
							<span class="input-group-addon">по</span>
							<input autocomplete="off" type="text" class="form-control input-sm" name="f_date_end" id="f_date_end" value="{{ request('f_date_end', '') }}"/>
						</div>
						<button type="submit" class="btn btn-default btn-sm">Показать</button>
					</div>
				</div>
				<!-- /.panel -->

				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<i class="fa fa-line-chart fa-fw"></i> Статистика
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						Корзин: <strong class="font-16">{{ $carts->total() }}</strong>, из них заказов <strong class="font-16">{{ $orders }} ({{ $carts->total() ? round($orders / $carts->total() * 100) : 0 }}%)</strong>
					</div>
				</div>
				<!-- /.panel -->

				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<i class="fa fa-table fa-fw"></i> Список корзин
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover table-condensed">
								<thead>
								<tr>
									<th class="text-right" width="100">#</th>
									<th width="200">Дата</th>
									<th width="150">IP</th>
									<th width="200">ОС/Браузер</th>
									<th class="text-right" width="100">Заказ #</th>
									<th>Чехлы/Товары</th>
								</tr>
								</thead>
								<tbody>
								<tr>
									<td><input autocomplete="off" name="f_id" value="{{ request('f_id', '') }}" type="text" class="form-control text-right" placeholder="#"></td>
									<td></td>
									<td><input autocomplete="off" name="f_ip" value="{{ request('f_ip', '') }}" type="text" class="form-control" placeholder="IP"></td>
									<td></td>
									<td><input autocomplete="off" name="f_order" value="{{ request('f_order', '') }}" type="text" class="form-control text-right" placeholder="Заказ #"></td>
									<td></td>
								</tr>
								</tbody>
								<tbody>
								@forelse($carts as $cart)
									<tr>
										<td class="text-right">{{ $cart->cart_id }}</td>
										<td>{{ $cart->cart_ts }}</td>
										<td>{{ $cart->cart_ip }}</td>
										<td>
											{{ (new Sinergi\BrowserDetector\Os($cart->cart_user_agent))->getName() }}, {{ (new Sinergi\BrowserDetector\Browser($cart->cart_user_agent))->getName() }}
										</td>
										<td class="text-right"><a href="{{ action('Admin@index', ['f_id' => $cart->cart_order_id]) }}">{{ $cart->cart_order_id }}</a></td>
										<td class="text-nowrap">
											@foreach($cart->cartSetCase as $case)
												<img width="100" class="m-r-5" src="{{ action('Admin@cartImg', ['id' => $case->cart_set_id]) }}">
											@endforeach
											@foreach($cart->cartSetProducts as $product)
												@if ($product->offer->product)
													<img width="100" class="m-r-5" src="{{ $product->offer->product->mainPhoto() }}">
												@endif
											@endforeach
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
						{{ $carts->links() }}
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
			</div>
		</div>
		<!-- /.row -->
	</form>
@endsection
@section('scripts')
	<script src="{{ url('js/admin-cart.js')}}"></script>
@endsection
