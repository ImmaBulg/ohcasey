<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Payment;

class CreatePaymentForOrder extends Job
{
    /**
     * @var Order
     */
    protected $order;

    /**
     * @var
     */
    protected $amount;

    /**
     * @param Order $order
     * @param int  $amount
     */
    public function __construct($order, $amount)
    {
        $this->order  = $order;
        $this->amount = $amount;
    }

    /**
     * @inheritdoc
     */
    public function handle()
    {
        return $this->create();
    }

    /**
     * Создать оплату.
     *
     * @return Payment
     */
    protected function create()
    {
        return Payment::create([
            'order_id' => $this->order->order_id,
            'amount'   => $this->amount,
        ]);
    }
}