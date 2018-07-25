@extends('admin.layout.master')
@section('content')
	<?php
		$interval = $startStat->diffInDays($end, true) + 1;
		$base = 100;
		$max  = max($statOrder->max('count'), $statCart->max('count'), $statSuccessOrder->max('count')) ?: 100;
		$date = $end->copy();
		$orderSum = $statOrder->sum('count');
		$cartSum  = $statCart->sum('count');
		$successOrderSum = $statSuccessOrder->sum('count');
	?>
	<form action="{{ url('admin') }}" method="get">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Заказы</h1>
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
						<div class="input-group">
							<input type="checkbox" id="with_trashed" name="with_trashed" value="1" {{ request('with_trashed', '') ? 'checked="checked"' : '' }}>
							<label for="with_trashed">Показать удаленные</label>
						</div><br>
						<button type="submit" class="btn btn-default btn-sm">Показать</button>
						<a id="getCsvBtn" class="btn btn-default">Товары в печать</a>
					</div>
				</div>
				<?php
				$cellWidth = 85;
				$blockWidth = ($cellWidth * ($interval + 1));
				?>
				<div class="panel panel-default" style="overflow-x:scroll;">
					<div class="panel-heading" style="width: {{$blockWidth}}px;">
						<i class="fa fa-bar-chart-o fa-fw"></i> Статистика по заказам c {{ $startStat->format('Y-m-d') }} по {{ $end->format('Y-m-d') }}
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body" style="width: {{$blockWidth}}px;">
						<div class="oh-order-stat clearfix">
							@for($i = 0; $i < $interval; $i++)
								<?php
									$cartSize         = ($base * ($statCart->has($i) ? $statCart->get($i)->count : 0) / $max) ?: 1;
									$orderSize        = ($base * ($statOrder->has($i) ? $statOrder->get($i)->count : 0) / $max) ?: 1;
									$successOrderSize = ($base * ($statSuccessOrder->has($i) ? $statSuccessOrder->get($i)->count : 0) / $max) ?: 1;
								?>
								<div class="oh-stat-day" style="width: {{$cellWidth}}px">
									<div class="oh-stat-bar-place" style="height: {{ $base }}px">
										<div class="oh-stat-cart" style="height: {{ $cartSize }}px; z-index: {{ $cartSize > $orderSize ? 1 : 3 }}">
											<div class="count">{{ ($statCart->has($i) ? $statCart->get($i)->count : 0) }}</div>
										</div>
										<div class="oh-stat-order" style="height: {{ $orderSize }}px; z-index: 2;">
											<div class="count">{{ ($statOrder->has($i) ? $statOrder->get($i)->count : 0) }}</div>
										</div>
										<div class="oh-stat-cart-case" style="height: {{ $successOrderSize }}px; z-index: 2;">
											<div class="count">{{ ($statSuccessOrder->has($i) ? $statSuccessOrder->get($i)->count : 0) }}</div>
										</div>
									</div>
									<div class="oh-stat-date">
										<div>{{ $date->day }}</div>
										@if ($date->day == 1 || $i == 0 || $i == $interval - 1)
											<div>{{ $date->formatLocalized('%B') }}</div>
										@else
											<div>&nbsp;</div>
										@endif
									</div>
								</div>
								<?php $date->subDay() ?>
							@endfor
						</div>
						<!--<div class="oh-stat-legend clearfix">
							<div class="pull-left"><span class="oh-stat-cart"></span> Корзин ({{ $cartSum }}),</div>
							<div class="pull-left"><span class="oh-stat-order"></span> Заказов ({{ $orderSum }}),</div>
							<div class="pull-left"><span class="oh-stat-cart-case"></span> Завершенных заказов ({{ $successOrderSum }}),</div>
							<div class="pull-left">Конверсия: <strong>{{ $cartSum ? round($orderSum / $cartSum * 100) . '%' : '-' }}</strong></div>
						</div>-->
						<?php
							$count_print_order = 0;
							$array_status_id_all = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14);
							$array_status_id_print = array(8, 3);
							$count_all_casey = 0;
							$count_print_casey = 0;
							foreach($orders as $one_order){
								if( in_array($one_order->order_status_id, $array_status_id_all) ) {
									if(isset($one_order->cart)){
										$count_all_casey += $one_order->cart->cartSetCase->count();
									}
								}
								if( in_array($one_order->order_status_id, $array_status_id_print) ) {
									if(isset($one_order->cart)){
										$count_print_casey += $one_order->cart->cartSetCase->count();
									}						
								}	
								
								if( in_array($one_order->order_status_id, $array_status_id_print) ) {
									$count_print_order++;
								}
							}
						?>
						<div class="oh-stat-legend clearfix">
							<?php /*
							<div class="pull-left"><span class="oh-stat-cart"></span> Корзин <strong>{{ $cartSum }}</strong>,</div>
							<div class="pull-left"><span class="oh-stat-order"></span> Заказов <strong>{{ $orderSum }}</strong>,</div>
							<div class="pull-left"><span class="oh-stat-cart-case" style="background-color: blue;"></span> Заказов в производстве: <strong><?=$count_print_order;?></strong>,</div>
							<div class="pull-left"><span class="oh-stat-cart-case"></span> Завершенных заказов <strong>{{ $successOrderSum }}</strong>,</div>
							<div class="pull-left">Всего чехлов: <strong><?=$count_all_casey;?></strong>,</div>
							<div class="pull-left">Чехлов в производстве: <strong><?=$count_print_casey;?></strong>,</div>
							<div class="pull-left">Конверсия: <strong>{{ $cartSum ? round($orderSum / $cartSum * 100) . '%' : '-' }}</strong></div>
							*/?>
							<div class="pull-left"><span class="oh-stat-cart"></span> Корзин <strong>{{ $counters['cartCount'] }}</strong>,</div>
							<div class="pull-left"><span class="oh-stat-order"></span> Заказов <strong>{{ $counters['total'] }}</strong>,</div>
							<div class="pull-left"><span class="oh-stat-cart-case" style="background-color: blue;"></span> Заказов в производстве: <strong>{{ $counters['ordersCountInProduction'] }}</strong>,</div>
							<div class="pull-left"><span class="oh-stat-cart-case"></span> Завершенных заказов <strong>{{ $counters['finished'] }}</strong>,</div>
							<div class="pull-left">Всего чехлов: <strong>{{ $counters['allCasesSum'] or 0 }}</strong>,</div>
							<div class="pull-left">Чехлов в производстве: <strong>{{ $counters['caseInProductionCount'] }}</strong>,</div>
							<div class="pull-left">Конверсия: <strong>{{ $counters['basketCoversion'] }}</strong></div>
						</div>
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<i class="fa fa-table fa-fw"></i> Список заказов
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						@include('admin.order.list')
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
	<script type="text/javascript">
		var SMS_TEMPLATES = {!!  \App\Models\SmsTemplate::all()  !!};
		var ORDERS = {!!  $orders->getCollection()  !!};
	</script>
	<script src="{{ url('js/admin-order.js') }}"></script>
@endsection
