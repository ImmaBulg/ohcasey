@extends('admin.layout.master')
@section('styles')
    <link rel="stylesheet" href="{{ _el('css/cart.css') }}">
@endsection
@section('scripts')
    {{---- Столько всего пришлось подрубить что-бы заработал калькулятор доставки ----}}
    <script type="text/javascript">
        var URL_STORAGE  = '{{ url('storage') }}';
        var URL_FONT = '{{ url('font/image') }}';
        var GEO = {!! json_encode((new App\Ohcasey\Ohcasey())->geo()) !!};
        var BASEURL = '{{ url('/') }}';
     </script>
    <script type="application/javascript" src="{{ url('js/perfect-scrollbar.jquery.js') }}"></script>
    <script type="application/javascript" src="{{ url('js/js.cookie.js') }}"></script>
    <script type="application/javascript" src="{{ _el('js/common.js') }}"></script>
    <script type="application/javascript" src="{{ _el('js/cart.js') }}"></script>
    <script type="application/javascript" src="{{ _el('js/admin-order-form.js') }}"></script>
    <script type="application/javascript" src="{{ url('js/validator.js') }}"></script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@endsection
@section('content')
    <style>
        .notSaved{
            border: 3px dashed orange;
        }
    </style>
    <form autocomplete="off" method="POST" action="{{route('admin.order.update', $order)}}" id="order-form">
        <div class="row">
            <div class="col-lg-6">
                <h3>Редактирование заказа #{{ $order->order_id }} {!! $order->trashed()  ? '<span style="color:red">(удаленный)</span>' : '' !!}</h3>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-lg-7">
                <button class="btn btn-default btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
                <button type="submit" name="implode" value="1" class="btn btn-outline btn-success"><i class="fa fa-link"></i> Объединить заказы</button>
                <a href="{{ route('admin.order.print', ['order' => $order, 'hash' => $order->order_hash]) }}" class="btn btn-outline btn-warning">Распечатать</a>
                <a href="{{ route('admin.order.recompile', ['order' => $order]) }}" class="btn btn-outline btn-primary"><i class="fa fa-list"></i> Пересоздать картинки</a>
            </div>
        </div>
        <div class="row" style="margin-top: 25px;">
            <div class="col-md-1 col-xs-1">
                Статус заказа
            </div>
            <div class="col-md-4 col-xs-4 order-status-admin">
                <select name="order[order_status_id]" style="width: 100%" class="oh-order-status status-colors js-was-changed" data-order-id="{{ $order->order_id }}">
                    @foreach($statuses as $status)
                        <option data-color="{{ $status->status_color }}" value="{{ $status->status_id }}" {{ $order->order_status_id == $status->status_id ? 'selected' : '' }}>{{ $status->status_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <div class="col-lg-6">
                <div class="panel panel-info">
                    <div class="panel-heading">Основная информация заказа</div>
                    <div class="panel-body">
                        <div class="form-group name">
                            <label class="email control-label" for="client_name">
                                Имя клиента
                            </label>
                            <input value="{{ $order->client_name }}" class="string form-control js-was-changed" id="client_name" name="order[client_name]" placeholder="Введите ФИО клиента">
                        </div>
                        <div class="form-group name">
                            <label class="email control-label" for="client_phone">
                                Телефон клиента
                            </label>
                            <input value="{{ $order->client_phone }}" class="string form-control js-was-changed" id="client_phone" name="order[client_phone]" placeholder="Введите телефон клиента">
                        </div>
                        <div class="form-group name">
                            <label class="email control-label" for="client_email">
                                Email клиента
                            </label>
                            <input value="{{ $order->client_email }}" class="string form-control js-was-changed" id="client_email" name="order[client_email]" placeholder="Введите email клиента">
                        </div>
                        <div class="form-group name">
                            <label class="email control-label" for="order_comment">
                                Комментарий оставленный клиентом
                            </label>
                            <input value="{{ $order->order_comment }}" class="string form-control js-was-changed" id="order_comment" name="order[order_comment]" placeholder="Введите комментарий клиента">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-info">
                    <div class="panel-heading">Информация о доставке</div>
                    <div class="panel-body">
                        <label style="cursor:pointer;" class="label label-default" for="change_delivery">Изменить доставку</label>
                        <input id="change_delivery" type="checkbox" name="change_delivery" value="1" style="display:none">
                    </div>
                    @include('_partial.delivery_info', [
                            'cart' => $order->cart,
                        ])
                    <div class="panel-body js-delivery-form js-was-changed" style="display:none">
                        @include('_partial.delivery_chooser', [
                            'cart'  => $order->cart,
                            'order' => $order,
                        ])
                    </div>
                </div>
            </div>
        </div>
        @if ($order->cart)
            <div class="row">
                <div class="col-lg-8">
                    <div class="panel panel-info">
                        <div class="panel-heading" id="cartSetCase">Товары</div>
                        <div class="panel-body">
                            @foreach ($order->cart->cartSetCase as $n => $item)
                                <table width="100%">
                                    <tbody>
                                    <tr>
                                        <td style="width: 250px;">
                                            <img src="{{ route('orders.showImage', ['order' => $order, 'hash' => $order->order_hash, 'img' => 'item_'.$item->cart_set_id.'.png']) }}"
                                                 alt="Элемент {{ $n + 1 }}" style="width: 200px;">
                                        </td>
                                        <td style="vertical-align: top">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3">
                                                    <label for="cart_set_device_{{$item->cart_set_id}}">
                                                        Устройство
                                                    </label>
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    {{ $item->device->device_caption }}
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top:10px;">
                                                <div class="col-lg-3  col-md-3">
                                                    <label for="cart_set_case_{{$item->cart_set_id}}">
                                                        Тип чехла
                                                    </label>
                                                </div>
                                                <div class="col-lg-6  col-md-6">
                                                    {{ $item->casey->case_caption }}
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top:10px;">
                                                <div class="col-lg-3  col-md-3">
                                                    <label>Цена</label>
                                                </div>
                                                <div class="col-lg-6  col-md-6">
                                                    <div class="col-lg-4">
                                                        {{ $item->item_cost }} <span class="icon-rouble"></span>
                                                    </div>
                                                   <div class="col-lg-2">
                                                       <div class="btn btn-outline btn-success edit-cost" data-type='case' data-cost="{{ $item->item_cost }}" data-id="{{ $item->cart_set_id }}">Изменить цену</div>
                                                   </div>
                                                </div>
                                            </div>

                                            <div class="row" style="margin-top:10px;">
                                                <div class="col-lg-3  col-md-3">
                                                    <label>Количество</label>
                                                </div>
                                                <div class="col-lg-6  col-md-6">
                                                    <div class="col-lg-4 col-md-4">
                                                        {{ $item->item_count }}
                                                    </div>
                                                    <div class="col-lg-2 col-md-2">
                                                        <div class="btn btn-outline btn-success edit-count" data-type='case' data-count="{{ $item->item_count }}" data-id="{{ $item->cart_set_id }}">Изменить кол-во</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="margin-top:10px;">
                                                <div class="col-lg-3  col-md-3">
                                                    <a href="{{route('admin.order.cart_set_case.edit', ['order' => $order, 'cartSetCase' => $item])}}" class="btn btn-outline btn-primary">Изменить</a>
                                                </div>
                                                <div class="col-lg-6  col-md-6">
                                                    <a data-toggle="confirmation"
                                                       data-title="Вы уверены?" class="btn btn-outline btn-danger" href="{{ route('admin.order.cart_set_case.remove', ['order' => $order, 'cartSetCase' => $item]) }}">Удалить</a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                @if (count($order->cart->cartSetCase) - 1 !== $n || count($order->cart->cartSetCase) === 1)
                                    <hr class="split-item">
                                @endif
                            @endforeach
                            @foreach ($order->cart->cartSetProducts as $n => $item)
                                <div class="row">
                                    <div class="col-lg-9">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                <img src="{{ $item->offer->product->mainPhoto() }}"
                                                     alt="Элемент {{ $n + 1 }}" style="width: 200px;">
                                            </div>
                                            <div class="col-lg-9">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3">
                                                        <label>
                                                            ID Товара
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        {{ $item->id }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3">
                                                        <label>
                                                            Название товара
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        {{ $item->offer->product->name }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3">
                                                        <label>
                                                            Свойства:
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="select">
                                                            <select id="offer_id" class="js-select-offer" name="offer_id" style="width: 100%;" data-id="{{ $item->id }}" data-order="{{ $order->order_id }}">
                                                                @foreach ($item->offer->product->offers as $offer)
                                                                    @if ($offer->id == $item->offer_id)
                                                                        <option selected value="{{$offer->id}}">{{$offer->optionValues->implode('title', ', ')}}</option>
                                                                    @else
                                                                        <option value="{{$offer->id}}">{{$offer->optionValues->implode('title', ', ')}}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        {{--{{ dump($item->offer->product->offers) }}
                                                        @if (isset($item->offer->optionValues))
                                                            {{ $item->offer->optionValues[0]->title }}
                                                        @endif--}}
                                                    </div>
                                                </div>
                                                @if ($item->offer->product->option_group_id == 9)
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3">
                                                            <label>
                                                                Размер
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="select">
                                                                <select name="size_id" id="size_id" class="js-select-size" style="width: 100%;" data-id="{{ $item->id }}" data-order="{{ $order->order_id }}">
                                                                    <option value="" disabled selected>Размер</option>
                                                                    @foreach ($sizes as $size)
                                                                        @if ($size->id == $item->size)
                                                                            <option selected value="{{$size->id}}">{{$size->title}}</option>
                                                                        @else
                                                                            <option value="{{$size->id}}">{{$size->title}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3">
                                                            <label>
                                                                Тип печати
                                                            </label>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6">
                                                            <div class="select">
                                                                <select name="print_id" id="print_id" class="js-select-print" style="width: 100%;" data-id="{{ $item->id }}"  data-order-id="{{ $order->order_id }}">
                                                                    <option value="" disabled selected>Тип печати</option>
                                                                    @foreach($prints as $print)
                                                                        @if ($print->id == $item->print)
                                                                            <option selected value="{{ $print->id }}">{{ $print->title }}</option>
                                                                        @else
                                                                            <option value="{{ $print->id }}">{{ $print->title }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="col-lg-3  col-md-3">
                                                        <label>Цена</label>
                                                    </div>
                                                    <div class="col-lg-6  col-md-6">
                                                        {{ $item->item_cost }}<span class="icon-rouble"></span>
                                                        <div class="btn btn-outline btn-success edit-cost" data-cost="{{ $item->item_cost }}" data-id="{{ $item->id }}">Изменить цену</div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="col-lg-3  col-md-3">
                                                        <label>Количество</label>
                                                    </div>
                                                    <div class="col-lg-6  col-md-6">
                                                        {{ $item->item_count }}
                                                        <div class="btn btn-outline btn-success edit-count" data-count="{{ $item->item_count }}" data-id="{{ $item->id }}">Изменить кол-во</div>
                                                    </div>
                                                </div>
                                                <div class="row" style="margin-top:10px;">
                                                    <div class="col-lg-6  col-md-6">
                                                        <a data-toggle="confirmation"
                                                           data-title="Вы уверены?" class="btn btn-outline btn-danger" href="{{ route('admin.order.cart_set_product.remove', ['order' => $order, 'cartSetProduct' => $item]) }}">Удалить</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($item->offer->product->option_group_id == 9)
                                        <div class="col-lg-3" style="padding-top: 2px">
                                            <div class="panel panel-info" data-itemid="{{ $item->id }}" data-orderid="{{ $order->order_id }}">
                                                <div class="panel-heading">Печать</div>
                                                <div class="panel-body">
                                                    <div class="form-group name">
                                                        <label for="date-send" class="date-send control-label">
                                                            Дата отправки в печать
                                                        </label>
                                                        <input id="date-send" type="text" class="string form-control js-date-send-picker" value="{{ $item->date_send }}">
                                                    </div>
                                                    <div class="form-group name">
                                                        <label for="supposed-date-back" class="supposed-date-back control-label">
                                                            Предполагаемая дата забора
                                                        </label>
                                                        <input type="text" id="supposed-date-back"
                                                               class="string form-control js-supposed-date-picker" value="{{ $item->supposed_date }}">
                                                    </div>
                                                    <div class="form-group name">
                                                        <label for="date-back" class="date-back control-label">
                                                            Дата забора из печати
                                                        </label>
                                                        <input type="text" id="date-back" class="string form-control js-date-back-picker" value="{{ $item->date_back }}">
                                                    </div>
                                                    <div class="form-group name">
                                                        <label for="print-status" class="print-status control-label">
                                                            Статус печати
                                                        </label> <br>
                                                        <select name="print-status" id="print-status" class="js-print-status-select" style="width: 100%;">
                                                            <option value="" disabled selected>Статус печати</option>
                                                            @foreach ($print_statuses as $print_status)
                                                                @if ($print_status->id == $item->print_status_id)
                                                                    <option selected value="{{ $print_status->id }}">{{ $print_status->title }}</option>
                                                                @else
                                                                    <option value="{{ $print_status->id }}">{{ $print_status->title }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                @if (count($order->cart->cartSetProducts) - 1 !== $n)
                                    <hr class="split-item">
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-warning">
                    <div class="panel-heading">Комментарий</div>
                    <div class="panel-body">
                        <textarea class="string form-control" id="consultant_note" name="order[consultant_note]">{{ $order->consultant_note }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row" style="margin-top: 10px;">
            <div class="col-lg-7">
                <button class="btn btn-default btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>
                <button type="submit" name="implode" value="1" class="btn btn-success"><i class="fa fa-link"></i> Объединить заказы</button>
                <a href="{{ route('admin.order.print', ['order' => $order, 'hash' => $order->order_hash]) }}" class="btn btn-warning">Распечатать</a>
                <a href="{{ route('admin.order.recompile', ['order' => $order]) }}" class="btn btn-outline btn-primary"><i class="fa fa-list"></i> Пересоздать картинки</a>
            </div>
        </div>--}}
    </form>

    <div class="row" style="margin-top: 25px;">
        <div class="col-lg-8">
            <div class="panel panel-info">
                <div class="panel-heading" id="cartSetProductPut">Добавить товар</div>
                <div class="panel-body">
                    <form method="post" action="{{ route('admin.order.cart_set_product.put', $order) }}">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="product_id"><b>Товар:</b></label>
                            </div>
                            <div class="col-md-6">
                                <select name="product_id" id="product_id" style="width: 100%" class="js-ajax-load-product">

                                </select>
                            </div>
                        </div>
                        <div class="js-offer-container" style="display: none">
                           {{--<div class="row">
                                <div class="col-md-3">
                                    <label for="select_offer_id"><b>Предложение:</b></label>
                                </div>
                                <div class="col-md-6">
                                    <select name="offer_id" id="select_offer_id" style="width: 100%">

                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="count"><b>Количество:</b></label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text" name="count" id="count">
                                </div>
                            </div>--}}
                            <div class="row">
                                <div class="col-md-3">
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn">Добавить</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

   {{-- <div class="row" style="margin-top: 10px;">
        <div class="col-lg-8">
            <div class="panel panel-info">
                <div class="panel-heading" id="specialItems">Сопутствующие товары</div>
                <div class="panel-body">
                    @foreach ($order->specialItems as $specialItem)
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6">
                                {{$specialItem->name}}
                            </div>
                            <div class="col-md-2">
                                {{$specialItem->price}} <span class="icon-rouble"></span>
                            </div>
                            <div class="col-md-4">
                                <a data-title="Вы уверены?" class="btn btn-outline btn-xs btn-danger"
                                   href="{{route('admin.special_item.delete', ['order' => $order, 'specialItem' => $specialItem])}}">
                                    Удалить
                                </a>
                            </div>
                        </div>
                    @endforeach
                    <hr>
                    <form autocomplete="off" method="POST" action="{{route('admin.special_item.store', $order)}}" id="special-item-form">
                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-4">
                                <input class="string form-control" type="text" name="name" placeholder="Название товара">
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-3">
                                <input class="string form-control" type="text" name="price" placeholder="Стоимость товара">
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline btn-primary" type="submit">Добавить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>--}}

    <div class="row" style="margin-top: 25px;">
        <div class="col-lg-8">
            <div class="panel panel-info">
                <div class="panel-heading" id="payment">Оплата</div>
                <div class="panel-body">
                    <div class="row">
                        @include('admin.payment.add_form', ['order' => $order])
                    </div>
                    <div class="row">
                        @include('admin.payment.short_list', ['order' => $order])
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
        /** @var \Illuminate\Database\Eloquent\Collection $logs */
        $logs = $order->orderLogs->sortByDesc('created_at');
        $logs->map(function (\App\Models\OrderLog $log) {
            $log->date = $log->created_at->format('H:i d.m.Y');
            return $log;
        });
        $logGroups = $logs->groupBy('date');
    ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-warning">
                <div class="panel-heading">История заказа <span class="log-expander js-log-expander">Развернуть</span></div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Дата</th>
                            <th>Пользователь</th>
                            <th>Информация</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($logGroups as $logs)
                            <tr>
                                <td>
                                    {{ $logs[0]->date }}
                                </td>
                                <td>
                                    {{ data_get($logs[0], 'user.name', '-Без пользователя-') }}
                                </td>
                                <td>
                                    <?php
                                    $text = [];
                                    foreach ($logs as $log) {
                                        if ($log->short_code != \App\Models\OrderLog::CUSTOM_CODE) {
                                            $text[] = 'Изменил "' . ($log->field_name ? trans('order.attributes.' . $log->field_name) : '') . '"
                                            c "' . $log->old_value . '" на "' . $log->new_value . '" ' . $log->description ;
                                        } else {
                                            $text[] = $log->description;
                                        }
                                    }
                                    ?>
                                    @foreach ($text as $line => $row)
                                        <p class="log-line js-log-line js-log-line-{{$line}}">{{$row}}</p>
                                    @endforeach
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">-</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <button form="order-form" class="btn btn-default btn-primary" type="submit"><i class="fa fa-check"></i> Сохранить</button>

    <div style="clear:both;"></div>

    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Изменить цену</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Старая цена: </label>
                        <label id="old-cost"></label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="new-cost form-control">
                        <input type="text" class="item-id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" class="save-cost btn btn-primary" data-type="">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModalCount" tabindex="-2" role="dialog" aria-labelledby="myModalLabelCount" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Изменить кол-во</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Текущее кол-во: </label>
                        <label id="old-count"></label>
                    </div>
                    <div class="form-group">
                        <input type="text" class="new-count form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="button" data-id='' data-count='' data-type='' class="save-count btn btn-primary">Сохранить</button>
                </div>
            </div>
        </div>
    </div>
@endsection