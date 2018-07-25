<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Support\SmsSender;
use Illuminate\Mail\Message;

/**
 * Отправка ссылки оплаты на email клиенту.
 *
 * @package App\Jobs
 */
class SendPaymentSms extends Job
{
    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @var SmsSender
     */
    protected $sender;

    /**
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->payment->load('order');
        $this->sender = app(SmsSender::class);
    }

    /**
     *
     */
    public function handle()
    {
        $this->send();
    }

    /**
     * Отправить.
     *
     * @throws \Exception
     */
    protected function send()
    {
        $payment = $this->payment;

        $order = $payment->order;
        if (! $order) {
            throw new \Exception('Undefined order');
        }

        $phone = $order->client_phone;
        if (! $phone) {
            throw new \Exception('Undefined phone');
        }

        $text = 'Сумма на оплату ' . $payment->amount . ' руб. по ссылке ' . route('payment.do_pay', ['paymentHash' => $payment->hash]);

        $this->sender->send($phone, $text);
    }
}