@include('_partial.js_data')
<div class="form-group">
    <select autocomplete="off" placeholder="Выберите страну" data-mandatory="true" name="country"
            class="select2 error-next" id="country" style="width: 100%">
        @if (isset($order))
            <option selected="selected" value="{{ data_get($order, 'country.country_iso') }}">{{ data_get($order, 'country.country_name_ru') }}</option>
        @else
            <option selected="selected" value="RU">Россия</option>
        @endif
    </select>
</div>
<?php
$hasCity = isset($order) && $order->city;
$deliveryType = isset($order) ? data_get($order, 'delivery.delivery_name') : '';
$date = '';
$hour = 0;
$minute = 0;
$hour_to = 0;
$minute_to = 0;
if (isset($order))
{
    $deliveryDate = isset($order) ? $order->delivery_time_from : '';
    $deliveryDate_to = isset($order) ? $order->delivery_time_to : '';
    if ($deliveryDate != '' && $deliveryDate_to != '')
    {
        list($date, $time) = explode (' ', $deliveryDate);
        list($hour, $minute) = explode (':', $time);
        list($hour_to, $minute_to) = explode(':', $deliveryDate_to);
    }
    else
    {
        $date = '';
        $hour = 0;
        $minute = 0;
        $hour_to = 0;
        $minute_to = 0;
    }

    }

?>
<div id="row-city" class="form-group {{ $hasCity ? '' : 'hidden' }}">
    <select autocomplete="off" placeholder="Выберите ваш город" name="city"
            data-mandatory-function="row.country == 'RU'"
            class="select2 error-next" id="city" style="width: 100%">
        @if (isset($order))
            <option selected="selected" value="{{ data_get($order, 'city.city_id') }}">{{ data_get($order, 'city.city_name_full') }}</option>
        @else
            <option value=""></option>
        @endif
    </select>
</div>

{{-- Шоурум --}}
<div style="display:none;" id="delivery-showroom" class="delivery hidden">
    <div class="form-group">
        <div class="pull-right">
            <div class="pull-left m-r-15"><span class="icon-clock"></span> <span class="time">2 дня</span></div>
            <div class="pull-left m-r-15"><span class="cost">0</span><span class="icon-rouble"></span></div>
            <div tabindex="0" href="#" data-placement="left"
                 class="cart-help-icon icon-question pull-left pointer" data-container="body"
                 data-toggle="popover" data-trigger="focus"
                 data-html="true" data-content='<div class="help-popover">
															<img class="icon" src="{{ url('img/delivery-showroom.png') }}">
															<div class="content">Шоурум находится по адресу м. Таганская, ул. Таганская 24 стр. 1, ежедневно с 12 до 20)</div></div>'>
            </div>
        </div>
        <?php /*<div class="caption">
            <label class="cart-cb">
                <input autocomplete="off" class="error-next" type="radio" name="delivery_type" data-mandatory="true"
                       value="showroom" {{$deliveryType == 'showroom' ? "checked=checked" : ''}}>
                <span class="point"><span></span></span>
                <span class="text">Самовывоз из шоурума</span>
                <span style="font-size:11px;">(по 100% предоплате)</span>
            </label>
        </div> */ ?>
    </div>

    <div class="delivery-sub hidden">
        <div class="form-group">
            <div class="form-control"><span class="icon-position"></span> м. Таганская, ул. Таганская 24 стр. 1,
                ежедневно с 12 до 20
            </div>
        </div>
        @if (!isset($order))
            <input autocomplete="off" data-pickpoint="true" data-courier="true" type="text"
                   class="form-group form-control date" readonly
                   name="delivery_date_showroom" placeholder="Дата визита"
                   data-mandatory-function="row.delivery_type == 'showroom'"
                   value="{{$order->delivery_date or ''}}"
            >
        @endif
    </div>
</div>

{{-- Самовывоз СДЭК --}}
<div id="delivery-pickpoint" class="delivery hidden">
    <div class="form-group">
        <div class="pull-right">
            {{--<div class="pull-left m-r-15"><span class="icon-clock"></span> <span class="time">?</span></div>--}}
            <div class="pull-left m-r-15"><span class="cost">?</span><span class="icon-rouble"></span></div>
            <div tabindex="0" href="#" data-placement="left"
                 class="cart-help-icon icon-question pull-left pointer" data-container="body"
                 data-toggle="popover" data-trigger="focus"
                 data-html="true" data-content='<div class="help-popover">
                                                        <img class="icon" src="{{ url('img/delivery-pickpoint.png') }}">
                                                        <div class="content">
                                                            Пункты самовывоза курьерской компании
                                                            <a target="_balnk" href="http://cdek.ru">СДЭК</a>.
                                                            Выберите этот способ доставки и на появившейся карте вашего
                                                            города выберите удобный вам пункт самовывоза.
                                                        </div>
                                                    </div>'>
            </div>
        </div>
        <div class="caption">
            <label class="cart-cb">
                <input autocomplete="off" class="error-next" type="radio" name="delivery_type" value="pickpoint"  {{$deliveryType == 'pickpoint' ? "checked=checked" : ''}}>
                <span class="point"><span></span></span>
                <span class="text">Пункты выдачи</span>
            </label>
        </div>
    </div>
    <div class="delivery-sub hidden">
        <div class="form-group">
            <input autocomplete="off" class="error-next" name="pvz" id="pvz" type="hidden" value=""
                   data-mandatory-function="row.delivery_type == 'pickpoint'">

            <div class="form-control pointer" id="pvz-select"><span class="icon-position"></span> <span class="pvz">Выберите пункт выдачи</span>
            </div>
            <div id="map-modal" class="map-modal hidden">
                <div class="content">
                    <div class="header">
                        Выберите пункт выдачи СДЭК
                        <span class="close">×</span>
                    </div>
                    <div id="map" class="body"></div>
                </div>
            </div>
        </div>
        {{-- <input autocomplete="off" data-pickpoint="true" data-courier="true" type="text"
                class="form-group form-control date" readonly
                name="delivery_date_pickpoint" placeholder="Дата визита"
                data-mandatory-function="row.delivery_type == 'pickpoint'"
                value="{{$order->delivery_date or ''}}"
                 >--}}
    </div>
</div>

{{-- Курьер СДЭК --}}
<div id="delivery-courier" class="delivery hidden">
    <div class="form-group">
        <div class="pull-right">
            {{--<div class="pull-left m-r-15"><span class="icon-clock"></span> <span class="time">?</span></div>--}}
            <div class="pull-left m-r-15"><span class="cost">?</span><span class="icon-rouble"></span></div>
            <div tabindex="0" href="#" data-placement="left"
                 class="cart-help-icon icon-question pull-left pointer" data-container="body"
                 data-toggle="popover" data-trigger="focus"
                 data-html="true" data-content='<div class="help-popover">
                                                        <img class="icon" src="{{ url('img/delivery-courier.png') }}">
                                                        <div class="content">
                                                            Доставка курьерской компанией <a target="_balnk" href="http://cdek.ru">СДЭК</a>.
                                                            Выберите этот способ доставки и кликните "дата визита",
                                                            чтобы увидеть ближайшую возможную дату доставки в вашем городе.
                                                        </div>
                                                    </div>'>
            </div>
        </div>
        <div class="caption">
            <label class="cart-cb">
                <input autocomplete="off" class="error-next" type="radio" name="delivery_type" value="courier" {{$deliveryType == 'courier' ? "checked=checked" : ''}}>
                <span class="point"><span></span></span>
                <span class="text">Курьером по России</span>
            </label>
        </div>
    </div>
    <div class="delivery-sub hidden">
        @if (!isset($order))
            <input autocomplete="off" data-pickpoint="true" data-courier="true" type="text"
                   class="form-group form-control date" readonly
                   name="delivery_date_courier" placeholder="Дата визита"
                   data-mandatory-function="row.delivery_type == 'courier'"
                   value="{{$order->delivery_date or ''}}"
            >
        @endif
        <input autocomplete="off" type="text" class="form-group form-control" name="courier_address"
               id="courier_address" placeholder="Адрес"
               data-mandatory-function="row.delivery_type == 'courier'"
               value="{{$order->delivery_address or ''}}"
        >
    </div>
</div>

{{-- Курьер МКАД --}}
<div id="delivery-courier_moscow" class="delivery hidden">
    <div class="form-group">
        <div class="pull-right">
            <div class="pull-left m-r-15"><span class="icon-clock"></span> <span class="time">2 дня</span></div>
            <div class="pull-left m-r-15"><span class="cost">250</span><span class="icon-rouble"></span></div>
            <div tabindex="0" href="#" data-placement="left"
                 class="cart-help-icon icon-question pull-left pointer" data-container="body"
                 data-toggle="popover" data-trigger="focus"
                 data-html="true" data-content='<div class="help-popover">
                                                        <img class="icon" src="{{ url('img/delivery-courier.png') }}">
                                                        <div class="content">
                                                            Доставка нашим курьером в пределах МКАД
                                                        </div>
                                                        </div>'>
            </div>
        </div>
        <div class="caption">
            <label class="cart-cb">
                <input autocomplete="off" class="error-next" type="radio" name="delivery_type" value="courier_moscow"  {{$deliveryType == 'courier_moscow' ? "checked=checked" : ''}}>
                <span class="point"><span></span></span>
                <span class="text">Курьер по Москве в пределах МКАД</span>
            </label>
        </div>
    </div>
    <div class="delivery-sub hidden">
        @if (!isset($order))
            <input autocomplete="off" data-pickpoint="true" data-courier="true" type="text"
                   class="form-group form-control date" readonly
                   name="delivery_date_courier_moscow" placeholder="Дата визита"
                   data-mandatory-function="row.delivery_type == 'courier_moscow'"
                   value="{{$order->delivery_date or ''}}"
            >
        @endif
        <input autocomplete="off" type="text" class="form-group form-control" name="courier_moscow_address"
               id="courier_moscow_address" placeholder="Адрес"
               data-mandatory-function="row.delivery_type == 'courier_moscow'"
               value="{{$order->delivery_address or ''}}"
        >
    </div>
</div>

{{-- Почта России --}}
<div id="delivery-post" class="delivery hidden">
    <div class="form-group">
        <div class="pull-right">
            <div class="pull-left m-r-15"><span class="icon-clock"></span> <span class="time">?</span></div>
            <div class="pull-left m-r-15"><span class="cost">300</span><span class="icon-rouble"></span></div>
            <div tabindex="0" href="#" data-placement="left"
                 class="cart-help-icon icon-question pull-left pointer" data-container="body"
                 data-toggle="popover" data-trigger="focus"
                 data-html="true" data-content='<div class="help-popover">
                                                        <img class="icon" src="{{ url('img/delivery-post.png') }}">
                                                        <div class="content">
                                                            Доставка в отделение Почты России
                                                        </div>
                                                    </div>'>
            </div>
        </div>
        <div class="caption">
            <label class="cart-cb">
                <input autocomplete="off" class="error-next" type="radio" name="delivery_type" value="post"  {{$deliveryType == 'post' ? "checked=checked" : ''}}>
                <span class="point"><span></span></span>
                <span class="text">Почта России</span>
            </label>
        </div>
    </div>
    <div class="delivery-sub hidden">
        <input autocomplete="off" type="text" class="form-group form-control" name="post_code"
               id="post_code" placeholder="Индекс"
               data-mandatory-function="row.delivery_type == 'post'"
        >
        <input autocomplete="off" type="text" class="form-group form-control" name="post_address"
               id="post_address" placeholder="Адрес"
               data-mandatory-function="row.delivery_type == 'post'"
        >
    </div>
</div>

@if (isset($order))
    <div id="delivery-time" class="form-group">
        <div class="form-group">
            <div class="pull-left row">
                <div class="col-lg-4 col-md-4">
                    <label for="delivery-choose-date">Дата</label>
                    <input autocomplete="off" type="text" class="form-control" name="delivery-choose-date" id="delivery-choose-date" value="{{ isset($order) ? (($order->delivery_date != '') ? $order->delivery_date : $date) : '' }}" style="height: 45px;">
                </div>
                <div class="col-lg-4 col-md-4">
                    <label for="delivery-choose-hour">Часы (с)</label>
                    <select name="delivery-choose-hour" id="delivery-choose-hour" class="js-select-hour" style="width: 100%;">
                        @for($i = 0; $i <= 24; $i++)
                            @if (intval($hour) == $i)
                                <option selected value="{{ $i }}">{{ ($i < 10) ? '0' . $i : $i  }}</option>
                            @else
                                <option value="{{ $i }}">{{ ($i < 10) ? '0' . $i : $i  }}</option>
                            @endif
                        @endfor
                    </select>
                </div>
                <div class="col-lg-4 col-md-4">
                    <label for="delivery-choose-minute">Минуты (с)</label>
                    <select name="delivery-choose-minute" id="delivery-choose-minute" class="js-select-minute" style="width: 100%;">
                        @for($i = 0; $i < 60; $i+=15)
                            @if (intval($minute) == $i)
                                <option selected value="{{ $i }}">{{ ($i < 10) ? '0' . $i : $i }}</option>
                            @else
                                <option  value="{{ $i }}">{{ ($i < 10) ? '0' . $i : $i }}</option>
                            @endif
                        @endfor
                    </select>
                </div>
                <div class="col-lg-4 col-md-4"></div>
                <div class="col-lg-4 col-md-4">
                    <label for="delivery-choose-hour">Часы (до)</label>
                    <select name="delivery-choose-hour" id="delivery-choose-hour" class="js-select-hour_to" style="width: 100%;">
                        @for($i = 0; $i <= 24; $i++)
                            @if (intval($hour_to) == $i)
                                <option selected value="{{ $i }}">{{ ($i < 10) ? '0' . $i : $i  }}</option>
                            @else
                                <option value="{{ $i }}">{{ ($i < 10) ? '0' . $i : $i  }}</option>
                            @endif
                        @endfor
                    </select>
                </div>
                <div class="col-lg-4 col-md-4">
                    <label for="delivery-choose-minute">Минуты (до)</label>
                    <select name="delivery-choose-minute" id="delivery-choose-minute" class="js-select-minute_to" style="width: 100%;">
                        @for($i = 0; $i < 60; $i+=15)
                            @if (intval($minute_to) == $i)
                                <option selected value="{{ $i }}">{{ ($i < 10) ? '0' . $i : $i }}</option>
                            @else
                                <option  value="{{ $i }}">{{ ($i < 10) ? '0' . $i : $i }}</option>
                            @endif
                        @endfor
                    </select>
                </div>
            </div>
        </div>
    </div>
@endif

@if (isset($order))
    <div class="form-group">
        <label>Стоимость доставки: </label>
        <input data-id="{{ $order->order_id }}" data-cost="{{ $order->delivery_amount }}" type="text" class="form-control" name="delivery_price" id="delivery_price" value="{{ $order->delivery_amount }}">
    </div>
@endif