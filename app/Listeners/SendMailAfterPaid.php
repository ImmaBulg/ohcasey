<?php

namespace App\Listeners;

use App\Events\PaymentPaid;
use App\Models\Payment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;

/**
 * Отправляет письма после оплаты закза.
 *
 * @package App\Listeners
 */
class SendMailAfterPaid implements ShouldQueue
{
    public function handle(PaymentPaid $event)
    {
        $payment = $event->getPayment();
        $order = $payment->order;
        if (! $order) {
            throw new \Exception('Undefined order');
        }

        $this->sendToClient($payment);
        $this->sendToAdmin($payment);
    }

    /**
     * Отправить письмо администратору.
     *
     * @param Payment $payment
     */
    protected function sendToAdmin(Payment $payment)
    {
        $order = $payment->order;
        $email = config('payment.mail_for_notification');

        \Mail::send(
            'mail.payment-successful-to-admin',
            ['payment' => $payment, 'order' => $order],
            function (Message $message) use ($email, $payment) {
                $message->from(env('MAIL_USERNAME'), 'OHCASEY');

                $message->to($email, $payment->order->client_name)
                    ->subject('OHCASEY: Оплата заказа #' . $payment->order->order_id . ', на сумму: ' . $payment->amount . ' руб.');
            }
        );
    }

    /**
     * Отправить письмо клиенту.
     *
     * @param Payment $payment
     */
    protected function sendToClient(Payment $payment)
    {
        $order = $payment->order;
        $email = $order->client_email;
        if ($email) {
            try {
                \Mail::send(
                    'mail.payment-successful',
                    ['payment' => $payment, 'order' => $order],
                    function (Message $message) use ($email, $payment) {
                        $message->from(env('MAIL_USERNAME'), 'OHCASEY');

                        $message->to($email, $payment->order->client_name)
                            ->subject('OHCASEY: Оплата заказа #' . $payment->order->order_id);
                    }
                );
            } catch (\Exception $e) {
                \Log::error('Не удалось отправить почту после оплаты.' . $e->getMessage());
            }
        }
    }
}