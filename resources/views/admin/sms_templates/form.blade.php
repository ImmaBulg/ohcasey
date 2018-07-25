@extends('admin.layout.master')
@section('content')
    <div class="col-md-12" style="margin-top:50px;">
        <div class="panel panel-primary">
            <div class="panel-heading">Шаблон СМС</div>
            <div class="panel-body">
                <form action="{{route('admin.sms_templates.store', ['smsTemplate' => $template]) }}" method="post" novalidate="novalidate">
                    <div style="display:none">
                        <input name="id" type="hidden" value="{{$template->id}}">
                    </div>

                    <div class="form-group name required">
                        <label class="email required control-label" for="name">
                            <abbr title="required">*</abbr> Название
                        </label>
                        <input value="{{ $template->name ?: Request::get('name', '') }}" class="string required form-control" id="name" name="name" placeholder="Введите название">
                        <p class="help-block">Название не учавствует непосредственно в отправке СМС, необходимо только для удобства</p>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="before_order_status_id">
                            Статус до
                        </label>
                        <select name="before_order_status_id" id="before_order_status_id" autocomplete="off" class="status-colors form-control">
                            <option data-color="#C71585" value="NULL" {{ !$template->before_order_status_id  ? 'selected=selected' : '' }}>(ещё не создан, без статуса)</option>
                            @foreach ($orderStatusList as $status)
                                <option data-color="{{ $status->status_color }}" value="{{ $status->status_id }}" {{ $template->before_order_status_id === $status->status_id ? 'selected="selected"' : '' }}>{{ $status->status_name }}</option>
                            @endforeach
                        </select>
                        <p class="help-block">Если статус не указан - считается, что заказ только что создан</p>
                    </div>

                    <div class="form-group required">
                        <label class="control-label required" for="before_order_status_id">
                            <abbr title="required">*</abbr> Статус после
                        </label>
                        <select name="after_order_status_id" id="after_order_status_id" autocomplete="off" class="status-colors form-control">
                            @foreach ($orderStatusList as $status)
                                <option data-color="{{ $status->status_color }}" value="{{ $status->status_id }}" {{ $template->after_order_status_id === $status->status_id ? 'selected="selected"' : '' }}>{{ $status->status_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group required">
                        <label class="control-label required" for="template">
                            <abbr title="required">*</abbr> Шаблон сообщение
                        </label>
                        <textarea name="template" id="template" class="form-control">{{ $template->template }}</textarea>
                        <p class="help-block">#ORDER_CODE# - будет заменён на номер заказа</p>
                    </div>

                    <input class="btn btn-default" type="submit" value="{{ $template->exists ? 'Обновить' : 'Добавить' }}">
                </form>
            </div>
        </div>
    </div>
@endsection