<?php

return [
    'attributes' => [
        'city_id'            => 'Город',
        'country_iso'        => 'Страна',
        'delivery_date'      => 'Дата доставки',
        'delivery_name'      => 'Способ доставки',
        'consultant_note'    => 'Технический комментарий',
        'order_amount'       => 'Сумма',
        'delivery_address'   => 'Адрес доставки',
        'delivery_amount'    => 'Стоимость доставки',
        'client_name'        => 'ФИО клиента',
        'client_phone'       => 'Телефон клиента',
        'order_comment'      => 'Комментарий клиента',
        'client_email'       => 'Email клиента',
        'order_status_id'    => 'Идентификатор статуса',
        'discount_amount'    => 'Сумма скидки',
        'utm'                => 'UTM метки',
        'date_send'          => 'Дата отправки в печать',
        'supposed_date'      => 'Предполагаемая дата забора',
        'date_back'          => 'Дата забора из печати',
        'print_status_id'    => 'Статус печати',
        'delivery_time'      => 'Время доставки',
        'delivery_time_from' => 'Время доставки (с)',
        'delivery_time_to'   => 'Время доставки (от)',
    ],

    'payment_status' => [
        \App\Models\Order::PROCESSED_ONLINE_PAYMENT => 'Онлайн оплата не завершена',
        \App\Models\Order::ALL_PAYMENTS_PAID        => 'Оплачен',
        \App\Models\Order::PARTIAL_PAYMENTS_PAID    => 'Частично оплачен',
        \App\Models\Order::NOONE_PAYMENTS_PAID      => 'Не оплачен',
        \App\Models\Order::WITHOUT_PAYMENTS         => 'Нет счетов',

        'color' => [
            \App\Models\Order::PROCESSED_ONLINE_PAYMENT => 'orange',
            \App\Models\Order::ALL_PAYMENTS_PAID        => 'green',
            \App\Models\Order::PARTIAL_PAYMENTS_PAID    => 'orange',
            \App\Models\Order::NOONE_PAYMENTS_PAID      => 'red',
            \App\Models\Order::WITHOUT_PAYMENTS         => 'grey',
        ]
    ],
];