<?php

return [
    'debug'                 => env('PAYMENT_DEBUG', true),
    'login'                 => env('PAYMENT_LOGIN'),
    'password1'             => env('PAYMENT_PASSWORD1'),
    'password2'             => env('PAYMENT_PASSWORD2'),
    'online_payment_method' => 1, //id записи онлайн оплаты в таблице payment_methods (по умолчанию первая запись см. PaymentMethodSeeder::class)
    'mail_for_notification' => env('MAIL_FOR_PAYMENT_NOTIFICATION'),
];