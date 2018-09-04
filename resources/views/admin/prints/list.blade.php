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
                    <button type="button" class="btn btn-default btn-sm" id="clear_filter">Очистить фильтры</button>
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
                                    <th colspan="8" class="text-center">ТОВАР</th>
                                    <th colspan="2" class="text-center">ПЕЧАТЬ</th>
                                    <th colspan="2" class="text-center">ДОСТАВКА</th>
                                </tr>
                                <tr>
                                    <th class="text-center">Дата заказа</th>
                                    <th style="width: 6%" class="text-center">ID товара</th>
                                    <th style="width: 6%" class="text-center">ID заказа</th>
                                    <th class="text-center">Тип товара</th>
                                    @if ($_REQUEST['sort'] === 'design')
                                        <th class="text-center" id="design_sort" style="cursor: pointer;">Наименование <span class="fa fa-arrow-circle-o-down"></span></th>
                                    @endif
                                    @if ($_REQUEST['sort'] === 'design_desc')
                                        <th class="text-center" id="design_sort" style="cursor: pointer;">Наименование <span class="fa fa-arrow-circle-o-up"></span></th>
                                    @endif
                                    @if ($_REQUEST['sort'] === 'delivery')
                                        <th class="text-center" id="design_sort" style="cursor: pointer;">Наименование <span class="fa fa-arrow-circle-o-down"></span></th>
                                    @endif
                                    @if ($_REQUEST['sort'] === 'delivery_desc')
                                        <th class="text-center" id="design_sort" style="cursor: pointer;">Наименование <span class="fa fa-arrow-circle-o-up"></span></th>
                                    @endif
                                    <th class="text-center">Размер</th>
                                    <th class="text-center">Печать</th>
                                    <th class="text-center">Кол-во</th>
                                    <th class="text-center">Статус печати</th>
                                    <th class="text-center">Когда напечатать</th>
                                    <th class="text-center" width="150px" id="delivery_sort" style="cursor: pointer;">Тип доставки <span class="fa hidden"></span></th>
                                    <th class="text-center">Дата доставки</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input autocomplete="off" id="f_date" name="f_date" value="{{ request('f_date', '') }}" type="text" class="form-control text-right" placeholder="Дата"></td>
                                    <td><input autocomplete="off" id="f_item" name="f_item" value="{{ request('f_item', '') }}" type="text" class="form-control text-right" placeholder="ID товара"></td>
                                    <td><input autocomplete="off" id="f_order" name="f_order" value="{{ request('f_order', '') }}" type="text" class="form-control text-right" placeholder="ID заказа"></td>
                                    <td><input autocomplete="off" id="f_type" name="f_type" value="{{ request('f_type', '') }}" type="text" class="form-control text-right" placeholder="Тип товара"></td>
                                    <td><input autocomplete="off" id="f_name" name="f_name" value="{{ request('f_name', '') }}" type="text" class="form-control text-right" placeholder="Наименование"></td>
                                    <td>
                                        <select id="f_size">
                                            <option></option>
                                            @foreach ($sizes as $size)
                                                @if ($size['id'] == request('f_size', ''))
                                                    <option class="option_select_{{ $size['id'] }}" selected value="{{ $size['id'] }}">{{ $size['title'] }}</option>
                                                @else
                                                    <option class="option_select_{{ $size['id'] }}" value="{{ $size['id'] }}">{{ $size['title'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select id="f_print_type">
                                            <option></option>
                                            @foreach ($print_types as $type)
                                                @if ($type['id'] == request('f_print_type', ''))
                                                    <option class="option_select_{{ $type['id'] }}" selected value="{{ $type['id'] }}">{{ $type['title'] }}</option>
                                                @else
                                                    <option class="option_select_{{ $type['id'] }}" value="{{ $type['id'] }}">{{ $type['title'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input autocomplete="off" id="f_count" name="f_count" value="{{ request('f_count', '') }}" type="text" class="form-control text-right" placeholder="Кол-во"></td>
                                    <td>
                                        <select id="f_status">
                                            <option></option>
                                            @foreach ($print_status as $status)
                                                @if ($status['id'] == request('f_status', ''))
                                                    <option class="option_select_{{ $status['id'] }}" selected value="{{ $status['id'] }}">{{ $status['title'] }}</option>
                                                @else
                                                    <option class="option_select_{{ $status['id'] }}" value="{{ $status['id'] }}">{{ $status['title'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input autocomplete="off" id="f_print_date" name="f_print_date" value="{{ request('f_print_date', '') }}" type="text" class="form-control text-right" placeholder="Дата печати"></td>
                                    <td>

                                        <select id="f_delivery_type">
                                            <option></option>
                                            @foreach ($delivery_types as $d_type)
                                                @if ($d_type->delivery_name == request('f_delivery_type', ''))
                                                    <option class="option_select_{{ $d_type->delivery_name }}" selected value="{{ $d_type->delivery_name }}">{{ $d_type->delivery_caption }}</option>
                                                @else
                                                    <option class="option_select_{{ $d_type->delivery_name }}" value="{{ $d_type->delivery_name }}">{{ $d_type->delivery_caption }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input autocomplete="off" id="f_delivery_date" name="f_count" value="{{ request('f_delivery_date', '') }}" type="text" class="form-control text-right" placeholder="Дата доставки"></td>
                                </tr>
                            </tbody>
                            <tbody>
                                @foreach ($rows as $row)
                                    {{--<tr>
                                        <td>{{ $order['order_date'] }}</td>
                                        <td>
                                            <button class="btn btn-default btn-sm btn-row" type="button" data-toggle="collapse" data-target=".collapse{{$order_id}}" aria-expanded="false">Развернуть</button>
                                        </td>
                                        <td>{{ $order_id }}</td>
                                        <td colspan="5"></td>
                                        <td><input type="text" disabled value="{{ $order['order_status'] }}"></td>
                                    </tr>--}}
                                    {{--<table >--}}
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
        var sort = '';

        $('.btn-row').click(function() {
            console.log($(this).text());
            if ($(this).text() === 'Развернуть')
                $(this).text('Свернуть');
            else
                $(this).text('Развернуть');
        });

        $('#f_status').select2({
            placeholder: 'Статус',
        });

        $('#f_size').select2({
            placeholder: 'Размер',
        });
        $('#f_print_type').select2({
            placeholder: 'Тип',
        });
        $('#f_delivery_type').select2({
            placeholder: 'Доставка',
            width: '150px',
        });

        $('#clear_filter').click(function() {
            let start = $('#f_date_start').val();
            let end = $('#f_date_end').val();
            document.location.href = "/admin/prints?f_date_start=" + start + "&f_date_end=" + end
        });

        $('#design_sort').click(function() {
            if (sort === 'delivery' || sort === 'delivery_desc')
                $('#delivery_sort').children('span').addClass('hidden').removeClass('fa-arrow-circle-o-down');

            if (sort === 'design') {
                $(this).children('span').removeClass('fa-arrow-circle-o-down').addClass('fa-arrow-circle-o-up');
                sort = 'design_desc';
            } else {
                $(this).children('span').removeClass('hidden').removeClass('fa-arrow-circle-o-up').addClass('fa-arrow-circle-o-down');
                sort = 'design';
            }
            console.log(sort);
        });

        $('#delivery_sort').click(function() {
            if (sort === 'design' || sort === 'design_desc')
                $('#design_sort').children('span').addClass('hidden').removeClass('fa-arrow-circle-o-down');
            if (sort === 'delivery') {
                $(this).children('span').removeClass('fa-arrow-circle-o-down').addClass('fa-arrow-circle-o-up');
                sort = 'delivery_desc';
            } else {
                $(this).children('span').removeClass('hidden').removeClass('fa-arrow-circle-o-up').addClass('fa-arrow-circle-o-down');
                sort = 'delivery';
            }
            console.log(sort);
        });

        $('#show_product').click(function() {
            let start = $('#f_date_start').val();
            let end = $('#f_date_end').val();
            let params = '';
            if ($('#f_date').val()) {
                params += ('&f_date=' + $('#f_date').val());
            }
            if ($('#f_item').val()) {
                params += ('&f_item=' + $('#f_item').val());
            }
            if ($('#f_order').val()) {
                params += ('&f_order=' + $('#f_order').val());
            }
            if ($('#f_status').val()) {
                params += ('&f_status=' + $('#f_status').val());
            }
            if ($('#f_type').val()) {
                params += ('&f_type=' + $('#f_type').val());
            }
            if ($('#f_size').val()) {
                params += ('&f_size=' + $('#f_size').val());
            }
            if ($('#f_print_type').val()) {
                params += ('&f_print_type=' + $('#f_print_type').val());
            }
            if ($('#f_count').val()) {
                params += ('&f_count=' + $('#f_count').val());
            }
            if ($('#f_print_date').val()) {
                params += ('&f_print_date=' + $('#f_print_date').val());
            }
            if ($('#f_delivery_type').val()) {
                params += ('&f_delivery_type=' + $('#f_delivery_type').val());
            }
            if ($('#f_delivery_date').val()) {
                params += ('&f_delivery_date=' + $('#f_delivery_date').val());
            }
            document.location.href = "/admin/prints?sort=" + sort + "&f_date_start=" + start + "&f_date_end=" + end + params;
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