<?php

namespace App\Jobs;

use App\Events\PaymentPaid;
use App\Models\Payment;
use Carbon\Carbon;

/**
 * Поменить "оплату" как оплечена.
 *
 * @package App\Jobs
 */
class MarkPaidPayment extends Job
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
    }

    /**
     * @inheritdoc
     */
    public function handle()
    {
        if (! $this->payment->isPaid()) {
            $this->payment->is_paid = true;
            $this->payment->paid_date = Carbon::now();
            $this->payment->save();
            event(new PaymentPaid($this->payment));
        }
    }
}