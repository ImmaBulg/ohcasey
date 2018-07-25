@extends('admin.layout.master')
@section('content')
    <div class="col-md-12" style="margin-top:50px;">
        <div class="panel panel-primary">
            <div class="panel-heading">Статус заказа</div>
            <div class="panel-body">
                <form action="{{route('admin.order_statuses.store', ['orderStatus' => $orderStatus]) }}" method="post" novalidate="novalidate">
                    <div style="display:none">
                        <input name="status_id" type="hidden" value="{{$orderStatus->status_id}}">
                    </div>

                    <div class="form-group name required">
                        <label class="email required control-label" for="status_name">
                            <abbr title="required">*</abbr> Название
                        </label>
                        <input value="{{ $orderStatus->status_name ?: Request::get('name', '') }}" class="string required form-control" id="status_name" name="status_name" placeholder="Введите название">
                    </div>

                    <div class="form-group name required">
                        <label class="email required control-label" for="status_color">
                            <abbr title="required">*</abbr> Цвет
                        </label>
                        <input value="{{ $orderStatus->status_color ?: Request::get('status_color', '') }}" class="string required form-control" id="status_color" name="status_color" placeholder="Введите цвет">
                        <p class="help-block">Любой CSS цвет (включая # для rgb)</p>
                    </div>

                    <div class="form-group name required">
                        <label class="email required control-label" for="status_success">
                            Этот статус - успешный результат
                        </label>
                        <input type="hidden" name="status_success" value="0">
                        <input {{ $orderStatus->status_success || Request::get('status_success', false) ? 'checked="checked" ' : '' }} type="checkbox" name="status_success" value="1" id="status_success">
                    </div>


                    <div class="form-group name required">
                        <label class="email required control-label" for="sort">
                            <abbr title="required">*</abbr> Сортировка
                        </label>
                        <input value="{{ $orderStatus->sort ?: Request::get('sort', '') }}" class="string required form-control" id="sort" name="sort" placeholder="Введите сортировку">
                        <p class="help-block">По убыванию (чем больше число - тем выше статус в списке).</p>
                        <p class="help-block" style="color:black;">Совет: используйте шаг равным 100, 200, 300...</p>
                    </div>

                    @if ($orderStatus->status_id >= 0)
                        <div class="form-group name required">
                            <label class="email required control-label" for="delete">
                                Архивный
                            </label>
                            <input {{ $orderStatus->deleted_at ? 'checked="checked" ' : ''}} type="checkbox" name="delete" value="1" id="delete">
                        </div>
                    @endif

                    <input class="btn btn-primary" type="submit" value="Сохранить">
                    <a href="{{ route('admin.order_statuses.index') }}" class="btn btn-default">Отмена</a>
                </form>
            </div>
        </div>
    </div>
@endsection