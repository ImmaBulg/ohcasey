@extends('admin.layout.master')
@section('content')
    <div id="toolbar">
        <div class="oh-toolbar clearfix">
            <a href="{{ route('admin.order_statuses.create') }}" class="btn btn-default">Добавить</a>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <i class="fa fa-table fa-fw"></i> Список статусов
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                    <tr>
                        <th width="50">id</th>
                        <th width="50">Сортировка</th>
                        <th width="200">Название</th>
                        <th>Успешный</th>
                        <th>Цвет</th>
                        <th>Архивирован</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($orderStatuses as $status)
                        <tr>
                            <td>
                                <a href="{{route('admin.order_statuses.edit', ['orderStatus' => $status])}}">
                                    {{$status->status_id}}
                                </a>
                            </td>
                            <td>
                                {{$status->sort}}
                            </td>
                            <td>
                                {{$status->status_name}}
                            </td>
                            <td>
                                {{ $status->status_success ? 'Да' : 'Нет' }}
                            </td>
                            <td>
                                <div style="width:100px;display:inline-block;">
                                    {{$status->status_color}}
                                </div>
                                <div style="width:15px;height:15px;display:inline-block;background-color:{{$status->status_color}}"></div>
                            </td>
                            <td>
                                {{ $status->deleted_at ? 'Да' : 'Нет' }}
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
        </div>
@endsection