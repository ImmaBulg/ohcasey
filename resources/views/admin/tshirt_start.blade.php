@extends('admin.layout.master')
@section('content')
    <form action="{{ url('admin/tshirt-stat') }}" method="get">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Футболки - статистика</h1>
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

                <div class="clearfix">
                    @forelse($tshirts as $tShirt => $value)
                        <div class="bg-stat-item">
                            <span>{{$value[0]}}</span>
                            <img style="margin: auto;" width="150px" height="150px" src="{{ url('/images/product/'.$tShirt) }}" />
                            <span>{{ $value[1] }}</span>
                        </div>
                    @empty
                        <p class="oh-info bg-info">Ничего нет</p>
                    @endforelse
                </div>

            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ url('js/admin-bg-stat.js') }}"></script>
@endsection
