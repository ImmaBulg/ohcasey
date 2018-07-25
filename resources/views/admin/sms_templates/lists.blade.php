@extends('admin.layout.master')
@section('content')
    <div id="toolbar">
        <div class="oh-toolbar clearfix">
            <a href="{{ route('admin.sms_templates.create') }}" class="btn btn-default">Добавить</a>
          </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading clearfix">
            <i class="fa fa-table fa-fw"></i> Список шаблонов
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-condensed">
                    <thead>
                    <tr>
                        <th width="50">id</th>
                        <th width="100">Название</th>
                        <th>Статус до</th>
                        <th>Статус после</th>
                        <th>Шаблон</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                            <tr>
                                <td><a href="{{ route('admin.sms_templates.edit', ['smsTemplate' => $template->id]) }}">{{ $template->id }}</a></td>
                                <td>{{ $template->name }}</td>
                                <td>
                                    <span style="color:{{ data_get($template, 'beforeOrderStatus.status_color') }}">
                                        {{ data_get($template, 'beforeOrderStatus.status_name') }}
                                    </span>
                                </td>
                                <td>
                                    <span style="color:{{ data_get($template, 'afterOrderStatus.status_color') }}">
                                        {{ data_get($template, 'afterOrderStatus.status_name') }}
                                    </span>
                                </td>
                                <td>{{ $template->template }}</td>
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