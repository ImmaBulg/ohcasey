<?php

namespace App\Listeners;

use App\Events\PaymentPaid;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\TransactionType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Message;

/**
 * @package App\Listeners
 */
class MakeTransactionForPayment implements ShouldQueue
{
    public function handle(PaymentPaid $event)
    {
        try {
            $payment = $event->getPayment();
            $order = $payment->order;
            Transaction::create([
                'order_id'            => $order->getKey(),
                'payment_id'          => $payment->getKey(),
                'amount'              => $payment->amount,
                'transaction_type_id' => TransactionType::ID_TYPE_FOR_INCOMING_BY_PAYMENT,
            ]);
        } catch (\Exception $e) {
            \Log::critical('Не удалось создать транзакцию:' . $e->getMessage() . ' ' . $e->getTraceAsString());
        }
    }
}