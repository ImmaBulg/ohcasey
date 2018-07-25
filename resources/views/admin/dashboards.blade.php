@extends('admin.layout.master')
@section('styles')
    <link href="{{ url('css/dashboards.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <form method="GET" action="{{route('admin.dashboards')}}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-calendar fa-fw"></i> Интервал
                    </div>
                    <div class="panel-body clearfix">
                        <div class="btn-group m-r-15 pull-left" role="group">
                            <button type="button" class="btn btn-default btn-sm btn-date"
                                    data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}"
                                    data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}">Сегодня</button>
                            <button type="button" class="btn btn-default btn-sm btn-date"
                                    data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subDay()->format('Y-m-d') }}"
                                    data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subDay()->format('Y-m-d') }}">Вчера</button>
                            <button type="button" class="btn btn-default btn-sm btn-date"
                                    data-from="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->subDay(7)->format('Y-m-d') }}"
                                    data-to="{{ (new \Carbon\Carbon(null, 'Europe/Moscow'))->format('Y-m-d') }}">Указать период</button>
                        </div>
                        <div class="input-daterange input-group pull-left m-r-15" id="report-range">
                            <input autocomplete="off" type="text" class="form-control input-sm" name="start" id="f_date_start" value="{{ $dateStart->format('Y-m-d') }}"/>
                            <span class="input-group-addon">по</span>
                            <input autocomplete="off" type="text" class="form-control input-sm" name="end" id="f_date_end" value="{{ $dateEnd->format('Y-m-d') }}"/>
                        </div>
                        <button type="submit" class="btn btn-default btn-sm">Показать</button>
						<a id="getCsvBtn" class="btn btn-default">Выгрузка товаров</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php $filters = ['f_date_end' => $dateEnd->format('Y-m-d'), 'f_date_start' => $dateStart->format('Y-m-d')]; ?>
    <div class="row stat-block-container">
		<h3>Сводка</h3> 
		<div class="row row_type_flex" style="margin-top:30px; width: 875px;">
			<div class="stat-cell">
				<div class="well">
					<h5>Всего корзин</h5>
					<h1>{{ $counters['cartCount'] }}</h1>
				</div>
			</div>
			<div class="stat-cell">
				<div class="well">
					<h5>Всего заказов</h5>
					<h1>{{ $counters['total'] }}</h1>
				</div>
			</div>
			
			<div class="stat-cell">
				<div class="well">
					<h5><b>Всего заказов ко всем корзинам</b></h5>
					<h1>{{ $counters['cartCount'] > 0 ? round($counters['total'] * 100 / $counters['cartCount'], 2)  : 0 }}</h1>
				</div>
			</div>
			
			<div class="stat-cell">
				<div class="well">
					<h5>Всего чехлов</h5>
					<h1>{{ $counters['allCasesSum'] or 0 }}</h1>
				</div>
			</div>
			<div class="stat-cell">
				<div class="well">
					<h5>Всего чехлов на заказ</h5>
					<h1>{{ $counters['total'] > 0 ? round($counters['allCasesSum'] / $counters['total'], 2) : 0 }}</h1>
				</div>
			</div>
			<div class="stat-cell">
				<div class="well">
					<h5>Заказов завершено</h5>
					<h1>{{ $counters['finished'] or 0 }}</h1>
				</div>
			</div>
			
			<div class="stat-cell">
				<div class="well">
					<h5><b>Завершенных заказов ко всем заказам</b></h5>
					<h1>{{ $counters['total'] > 0 ? round($counters['finished'] * 100 / $counters['total'], 2) : 0 }}</h1>
				</div>
			</div>
			<div class="stat-cell">
				<div class="well">
					<h5><b>Завершенных заказов ко всем корзинам</b></h5>
					<h1>{{ $counters['cartCount'] > 0 ? round($counters['finished'] * 100 / $counters['cartCount'], 2) : 0 }}</h1>
				</div>
			</div>
			
			<div class="stat-cell">
				<div class="well">
					<h5>Чехлов завершено</h5>
					<h1>{{ $counters['caseFinishedSum'] or 0 }}</h1>
				</div>
			</div>
			<div class="stat-cell">
				<div class="well">
					<h5>Чехлов на заказ завершено</h5>
					<h1>{{ $counters['finished'] > 0 ? round($counters['caseFinishedSum'] / $counters['finished'], 2) : 0 }}</h1>
				</div>
			</div>
			
			<div class="stat-cell">
				<div class="well">
					<h5><b>Завершенных чехлов ко всем чехлам</b></h5>
					<h1>{{ $counters['allCasesSum'] > 0 ? round($counters['caseFinishedSum'] * 100 / $counters['allCasesSum'], 2) : 0 }}</h1>
				</div>
			</div>
			
			<div class="stat-cell">
				<div class="well">
					<h5>Выручка</h5>
					<h1>{{ $counters['proceeds']['pure'] }}</h1>
				</div>
			</div>
		</div>
		
        <h3>Заказы</h3>
        <div class="row" style="margin-top: 20px; display:block; width: 1090px;">
            <div class="stat-cell">
                <a href="{{route('admin.order.list', $filters)}}" class="well" style="display:block; background-color: #000; color: #fff">
                    <h5>{{ trans('dashboards.name.total') }}</h5>
                    <h1>{{ $counters['total'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <div class="well" style="display:block;">
                    <h5>{{ trans('dashboards.name.total_cases') }}</h5>
                    <h1>{{ $counters['allCasesSum'] or 0 }}</h1>
                </div>
            </div>
            <div class="stat-cell">
                <a
                href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_NEW)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block;background-color: #fff; color: #000;">
                    <h5>{{ trans('dashboards.name.new') }}</h5>
                    <h1>{{ $counters['new'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <a
                        href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_DESIGNING)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block;background-color: #fff; color: #2222ff;">
                    <h5>{{ trans('dashboards.name.designing') }}</h5>
                    <h1>{{ $counters['designing'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <a
                        href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_IN_PRINT)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block;background-color: #fff; color: #2222ff;">
                    <h5>Заказов в печати</h5>
                    <h1>{{ $counters['printing'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <a
                href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_WAIT_PAYMENT)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block;background-color:#fff; color: #ff3333;">
                    <h5>{{ trans('dashboards.name.wait_payment') }}</h5>
                    <h1>{{ $counters['wait_payment'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <a
                        href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_THINKS)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block;background-color:#fff; color: #ff3333;">
                    <h5>{{ trans('dashboards.name.thinks') }}</h5>
                    <h1>{{ $counters['thinks'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <a
                href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_NO_CONNECTION)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block;background-color:#fff; color: #ff3333;">
                    <h5>{{ trans('dashboards.name.no_connection') }}</h5>
                    <h1>{{ $counters['no_connection'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <a
                        href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_CANCELED)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block;background-color:#fff; color: #ff3333;">
                    <h5>{{ trans('dashboards.name.canceled') }}</h5>
                    <h1>{{ $counters['canceled'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <a
                        href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_PAID)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block;background-color:#fff; color: #9BF47E;">
                    <h5>{{ trans('dashboards.name.paid') }}</h5>
                    <h1>{{ $counters['paid'] }}</h1>
                </a>
            </div>
            <div class="stat-cell">
                <a
                        href="{{
                    route('admin.order.list', $filters + [
                        'f_status' => \App\Models\OrderStatus::withTrashed()->where('status_id', \App\Models\OrderStatus::STATUS_ID_FINISHED)
                            ->first()
                            ->status_name
                    ])
                }}" class="well" style="display:block; background-color:#fff; color: #9BF47E;">
                    <h5>{{ trans('dashboards.name.finished') }}</h5>
                    <h1>{{ $counters['finished'] }}</h1>
                </a>
            </div>
        </div>
		
		@if( !empty($counters['deliveries']) )
		<h3>Заказы по типу доставки</h3>
        <div class="row row_type_flex" style="margin-top:30px; width: 875px;">
			@foreach($counters['deliveries'] as $deliveryType)
            <div class="stat-cell">
                <div class="well" style="background-color:#fff; color: #000;">
                    <h5>{{ $deliveryType->delivery_caption or 'Нет типа доставки' }}</h5>
                    <h1>{{ $deliveryType->count }}</h1>
                </div>
            </div>
			@endforeach
		</div>
		@endif
		
		@if( !empty($counters['ordersByCities']) )
		<h3>Заказы по городам</h3>
        <div class="row row_type_flex" style="margin-top:30px; width: 875px;">
			@if( !empty($counters['ordersByCities']['Москва']) )
				<div class="stat-cell">
					<div class="well">
						<h5>{{ $counters['ordersByCities']['Москва']->city_name_full }}</h5>
						<h1>{{ $counters['ordersByCities']['Москва']->count }}</h1>
					</div>
				</div>
			@endif
			@if( !empty($counters['ordersByCities']['Санкт-Петербург']) )
				<div class="stat-cell">
					<div class="well">
						<h5>{{ $counters['ordersByCities']['Санкт-Петербург']->city_name_full }}</h5>
						<h1>{{ $counters['ordersByCities']['Санкт-Петербург']->count }}</h1>
					</div>
				</div>
			@endif
			@if( !empty($counters['ordersByCities']['Другие']) )
				<div class="stat-cell show-all-orders-by-cities">
					<div class="well">
						<h5>Другие</h5>
						<h1>{{ $counters['ordersByCities']['Другие']['count'] }}</h1>
						<h5>Показать/скрыть все</h5>
					</div>
				</div>
				<div class="row row_type_flex">
					@foreach ($counters['ordersByCities']['Другие'] as $cityName => $city)
						@if($cityName == 'count')
							@continue
						@endif
						<div class="stat-cell hidden-orders-by-cities">
							<div class="well">
								<h5>{{ $city->city_name_full or  $cityName}}</h5>
								<h1>{{ $city->count }}</h1>
							</div>
						</div>
					@endforeach
				</div>
			@endif
		</div>
		@endif
		
		@if( !empty($counters['orderCountByDevice']) )
			<h3>Заказы по типам  устройств</h3> 
			<div class="row row_type_flex" style="margin-top:30px; width: 875px;">
				@foreach ($counters['orderCountByDevice'] as $deviceName => $deviceCount)
					<div class="stat-cell">
						<div class="well">
							<h5>{{ $deviceName }}</h5>
							<h1>{{ $deviceCount }}</h1>
						</div>
					</div>
				@endforeach
			</div>
		@endif
		
		@if( !empty($counters['orderCountByDevice']) )
			<h3>Заказы по типам чехлов</h3> 
			<div class="row row_type_flex" style="margin-top:30px; width: 875px;">
				@foreach ($counters['orerCountByCaseType'] as $caseType => $count)
					<div class="stat-cell">
						<div class="well">
							<h5>{{ $caseType }}</h5>
							<h1>{{ $count }}</h1>
						</div>
					</div>
				@endforeach
			</div>
		@endif
		
		@if( !empty($counters['oldUsersCount']) && !empty($counters['newUsersCount']) )
			<h3>Заказы по типам клиентов</h3> 
			<div class="row row_type_flex" style="margin-top:30px; width: 875px;">
				<div class="stat-cell">
					<div class="well">
						<h5>Новые</h5>
						<h1>{{ $counters['newUsersCount'] }}</h1>
					</div>
				</div>
				<div class="stat-cell">
					<div class="well">
						<h5>Старые</h5>
						<h1>{{ $counters['oldUsersCount']  }}</h1>
					</div>
				</div>
			</div>
		@endif
		
		@if( !empty($counters['orderUtm']) )
			<h3>Заказы по типам трафика</h3> 
			<div class="row row_type_flex" style="margin-top:30px; width: 875px;">
				@foreach ($counters['orderUtm'] as $utmName => $count)
					<div class="stat-cell">
						<div class="well">
							<h5>{{ $utmName }}</h5>
							<h1>{{ $count }}</h1>
						</div>
					</div>
				@endforeach
			</div>
		@endif
		
		@if( !empty($counters['promoCodesCounts']) )
			<h3>Заказы по типам акции</h3> 
			<div class="row row_type_flex" style="margin-top:30px; width: 875px;">
				@foreach ($counters['promoCodesCounts'] as $promoCode => $promo)
					<div class="stat-cell">
						<div class="well">
							<h5>{{ $promo['name'] }}</h5>
							<h1>{{ $promo['count'] }}</h1>
						</div>
					</div>
				@endforeach
			</div>
		@endif
		
		<h3>Заказов с надписями/смайлами</h3> 
		<div class="row row_type_flex" style="margin-top:30px; width: 875px;">
			<div class="stat-cell">
				<div class="well">
					<h5>С надписями</h5>
					<h1>{{ $counters['ordersWithTextCount'] }}</h1>
				</div>
			</div>
			<div class="stat-cell">
				<div class="well">
					<h5>Со смайлами</h5>
					<h1>{{ $counters['ordersWithSmileCount'] }}</h1>
				</div>
			</div>
		</div>
		
        <h3>Корзины</h3>
        <div class="row row_type_flex" style="margin-top:30px; width: 875px;">
            <div class="stat-cell">
                <div class="well">
                    <h5>Всего корзин</h5>
                    <h1>{{ $counters['cartCount'] }}</h1>
                </div>
            </div>
            <div class="stat-cell">
                <div class="well">
                    <h5>Чехлов на корзину всего</h5>
                    <h1>{{ $counters['casesPerCart'] }}</h1>
                </div>
            </div>
            <div class="stat-cell">
                <div class="well">
                    <h5>Чехлов в производстве на корзину</h5>
                    <h1>{{ $counters['casesPerCartInProduction'] }}</h1>
                </div>
            </div>
            <div class="stat-cell">
                <div class="well">
                    <h5>Чек на корзину всего</h5>
                    <h1>{{ $counters['checkPerCart'] }} <span class="icon-rouble"></span></h1>
                </div>
            </div>
            <div class="stat-cell">
                <div class="well">
                    <h5>Чек на корзину в производстве</h5>
                    <h1>{{ $counters['checkPerCartInProduction'] }} <span class="icon-rouble"></span></h1>
                </div>
            </div>
            <div class="stat-cell">
                <div class="well">
                    <h5>Конверсия из корзин в заказ всего</h5>
                    <h1>{{ $counters['basketCoversion'] }}%</h1>
                </div>
            </div>
            <div class="stat-cell">
                <div class="well">
                    <h5>Конверсия из корзин в производстве в заказ</h5>
                    <h1>{{ $counters['basketCoversionInProduction'] }}%</h1>
                </div>
            </div>
        </div>
        <h3>Выручка</h3>
        <div class="row row_type_flex">
			{{--{{ dump($counters['proceeds']) }}--}}
            <div class="stat-cell">
                <div class="well">
                    <h5>Сумма заказов общая</h5>
                    <h1>{{ $counters['proceeds']['orderSum'] }} <span class="icon-rouble"></span></h1>
                </div>
            </div>
            <div class="stat-cell">
                <div class="well">
                    <h5>Сумма заказов со статусами макет, печать, доставлен</h5>
                    <h1>{{ $counters['proceeds']['orderSumInProduction'] }} <span class="icon-rouble"></span></h1>
                </div>
            </div>
            <div class="stat-cell">
                <div class="well">
                    <h5>Выручка </h5>
                    <h1>{{ $counters['proceeds']['pure'] }} <span class="icon-rouble"></span></h1>
                </div>
            </div>
		</div>
		
		
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(function () {
            $('#report-range input').datepicker({
                format: "yyyy-mm-dd",
                todayBtn: true,
                language: "ru"
            });

            $('.btn-date').click(function () {
                $('#f_date_start').datepicker('update', $(this).data('from'));
                $('#f_date_end').datepicker('update', $(this).data('to'));
            })
        });
    </script>
@endsection