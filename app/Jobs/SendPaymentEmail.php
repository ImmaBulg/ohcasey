<?php

namespace App\Jobs;

use App\Models\Payment;
use Illuminate\Mail\Message;

/**
 * Отправка ссылки оплаты на email клиенту.
 *
 * @package App\Jobs
 */
class SendPaymentEmail extends Job
{
    /**
     * @var Payment
     */
    protected $payment;

    /**
     * @param Payment $payment
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->payment->load('order');
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

        $email = $order->client_email;
        if (! $email) {
            throw new \Exception('Undefined email');
        }

        \Mail::send(
            'mail.payment',
            ['payment' => $payment, 'order' => $order],
            function (Message $message) use ($email, $payment) {
                $message->from(env('MAIL_USERNAME'), 'OHCASEY');

                $message->to($email, $payment->order->client_name)
                    ->subject('OHCASEY: Оплата заказа #' . $payment->order->order_id);
            }
        );
    }
}