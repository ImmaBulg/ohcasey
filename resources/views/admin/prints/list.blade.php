@extends('admin.layout.master')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Товары на печать</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calendar fa-fw"></i> Интервал
                </div>
                <div class="panel-body">
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
                    <button type="button" class="btn btn-default btn-sm" id="show_product">Показать</button>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <i class="fa fa-table fa-fw"></i> Список товаров
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-condensed table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="9" class="text-center">ТОВАР</th>
                                    <th colspan="2" class="text-center">ПЕЧАТЬ</th>
                                    <th colspan="2" class="text-center">ДОСТАВКА</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Дата заказа</th>
                                    <th class="text-center">ID товара</th>
                                    <th class="text-center">ID заказа</th>
                                    <th class="text-center">Тип товара</th>
                                    <th class="text-center">Наименование</th>
                                    <th class="text-center">Крой</th>
                                    <th class="text-center">Размер</th>
                                    <th class="text-center">Печать</th>
                                    <th class="text-center">Кол-во</th>
                                    <th class="text-center">Статус печати</th>
                                    <th class="text-center">Когда напечатать</th>
                                    <th class="text-center">Тип доставки</th>
                                    <th class="text-center">Дата доставки</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr>
                                        <td>{{ $row['order_time'] }}</td>
                                        <td>
                                            @if ($row['print_status'] === 'Отправлен в печать срочно')
                                                <span style="color: red;">{{ $row['product_id'] }}</span>
                                            @else
                                                {{ $row['product_id'] }}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.order.show', \App\Models\Order::where(['order_id' => $row['order_id']])->first()) }}">
                                                {{ $row['order_id'] }}
                                            </a>
                                        </td>
                                        <td>{{ $row['product_type'] }}</td>
                                        <td>{{ $row['background_name'] }}</td>
                                        <td>{{ $row['cutting_name'] }}</td>
                                        <td>{{ $row['print_size'] }}</td>
                                        <td>
                                            @if ($row['print_type'] === 'Прямая печать')
                                                <span style="color: red;">{{ $row['print_type'] }}</span>
                                            @else
                                                {{ $row['print_type'] }}
                                            @endif
                                        </td>
                                        <td>{{ $row['product_count'] }}</td>
                                        <td>
                                            <select class="print_status_select" data-id="{{ $row['product_id'] }}">
                                                @foreach ($print_status as $status)
                                                    @if ($status['title'] === $row['print_status'])
                                                        <option class="option_select_{{ $status['id'] }}" selected value="{{ $status['id'] }}">{{ $status['title'] }}</option>
                                                    @else
                                                        <option class="option_select_{{ $status['id'] }}" value="{{ $status['id'] }}">{{ $status['title'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>{{ $row['print_date'] }}</td>
                                        <td>{{ $row['delivery_name'] }}</td>
                                        <td>{{ $row['delivery_date'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ url('js/prints_page.js') }}"></script>
    <script>
        $('#show_product').click(function() {
            let start = $('#f_date_start').val();
            let end = $('#f_date_end').val();

            document.location.href = "/admin/prints?f_date_start=" + start + "&f_date_end=" + end;
        });
    </script>
@endsection

@section('styles')
    <style>
        span[title="Отправлен в печать срочно"] {
            color: red !important;
        }
    </style>
@endsection