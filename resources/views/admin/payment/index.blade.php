@extends('admin.layout.master')
@section('content')
<form action="{{ route('admin.payment.payment_list') }}" method="get">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Оплаты</h1>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calendar fa-fw"></i> Интервал
                </div>
                <div class="panel-body clearfix">
                    <div class="btn-group m-r-15 pull-left" role="group">
                        <button type="button" class="btn btn-default btn-sm btn-date"
                                data-from="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                data-to="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">Сегодня</button>
                        <button type="button" class="btn btn-default btn-sm btn-date"
                                data-from="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}"
                                data-to="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}">Вчера</button>
                        <button type="button" class="btn btn-default btn-sm btn-date"
                                data-from="{{ \Carbon\Carbon::now()->subDay(7)->format('Y-m-d') }}"
                                data-to="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">Указать период</button>
                    </div>
                    <div class="input-daterange input-group pull-left m-r-15" id="report-range">
                        <input autocomplete="off" type="text" class="form-control input-sm" name="created_at_from" id="created_at_from" value="{{ request('created_at_from', '') }}"/>
                        <span class="input-group-addon">по</span>
                        <input autocomplete="off" type="text" class="form-control input-sm" name="created_at_to" id="created_at_to" value="{{ request('created_at_to', '') }}"/>
                    </div>
                    <button type="submit" class="btn btn-default btn-sm btn-primary">Показать</button>
                    <br >
                    <button type="submit" name="check" value="1" class="btn btn-default btn-sm btn-warning">Проверить неоплаченные оплаты</button>
                    <span style="font-size:12px;color:grey;">(оплаты для проверки будут отифльтрованы по текущему фильтру)</span>
                </div>
            </div>
        </div>
    </div>
    @if ($wasCheck)
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <i class="fa fa-table fa-fw"></i> Перепроверка установила факт оплаты для:
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <th width="100">#</th>
                                <th width="100"># заказа</th>
                                <th>Дата выставления</th>
                                <th width="200">Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                        <tbody>
                            @forelse ($markedPayments as $payment)
                                <tr>
                                    <td> {{ $payment->id }}</td>
                                    <td>
                                        @if ($payment->order->trashed())
                                            <a href="{{ route('admin.order.show', $payment->order) }}" class="trashed-order-link">
                                                {{ $payment->order->order_id }} удален
                                            </a>
                                        @else
                                            <a href="{{ route('admin.order.show', $payment->order) }}">
                                                {{ $payment->order->order_id }}
                                            </a>
                                        @endif
                                    </td>
                                    <td> {{ $payment->created_at->format('d.m.Y H:i') }}</td>
                                    <td> {{ $payment->amount }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="100%" class="text-center bg-warning"><strong>Ничего нет</strong></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <i class="fa fa-table fa-fw"></i> Список оплат
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                        <tr>
                            <th width="100">#</th>
                            <th width="100"># заказа</th>
                            <th>Дата выставления</th>
                            <th width="200">Сумма</th>
                            <th width="200">Статус</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input autocomplete="off" name="id" value="{{ request('id', '') }}" type="text" class="form-control text-right" placeholder="#"></td>
                            <td><input autocomplete="off" name="order_id" value="{{ request('order_id', '') }}" type="text" class="form-control text-right" placeholder="#"></td>
                            <td></td>
                            <td></td>
                            <td>
                                <select class="form-control" name="is_paid" style="width:200px;">
                                    <option value="">Все</option>
                                    <option {{ request('is_paid', null) == 'y' ? 'selected="selected"' : '' }} value="y">Оплачен</option>
                                    <option {{ request('is_paid', null) == 'n' ? 'selected="selected"' : '' }} value="n">Не оплачен</option>
                                </select>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                    <tbody>
                        @forelse ($payments as $payment)
                            <tr class="js-payment-container">
                                <td> {{ $payment->id }}</td>
                                <td>
                                    @if ($payment->order->trashed())
                                        <a href="{{ route('admin.order.show', $payment->order) }}" class="trashed-order-link">
                                            {{ $payment->order->order_id }} удален
                                        </a>
                                    @else
                                        <a href="{{ route('admin.order.show', $payment->order) }}">
                                            {{ $payment->order->order_id }}
                                        </a>
                                    @endif
                                </td>
                                <td> {{ $payment->created_at->format('d.m.Y H:i') }}</td>
                                <td> {{ $payment->amount }}</td>
                                <td> {{ $payment->isPaid() ? 'Оплачено' : 'Не оплачено' }}</td>
                                <td>
                                    @if (!$payment->isPaid())
                                        <a class="js-payment-delete" href="{{route('admin.payment.ajax_delete', $payment)}}">
                                            Удалить
                                        </a>
                                    @endif
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
            {{ $payments->links() }}
        </div>
    </div>
</form>

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
                $('#created_at_from').datepicker('update', $(this).data('from'));
                $('#created_at_to').datepicker('update', $(this).data('to'));
            })
        });
    </script>
@endsection