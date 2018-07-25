<?php

namespace App\Listeners;

use App\Events\PaymentPaid;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Сбросить атрибут processed_online_payment заказа на false.
 *
 * @package App\Listeners
 */
class FlushOrderProcessOnlinePaymentAttribute implements ShouldQueue
{
    public function handle(PaymentPaid $event)
    {
        $payment = $event->getPayment();
        $order = $payment->order;
        if (! $order) {
            throw new \Exception('Undefined order');
        }
        $order->processed_online_payment = false;
        $order->save();
    }
}