<?php

namespace App\Jobs;

use App\Events\PaymentPaid;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * Проверить все оплаты на факт оплаченности и проставить им статус оплачен,
 * если оплата была оплачена, но почему-то у нас в бд признак оплаты отсутствует.
 *
 * @package App\Jobs
 */
class CheckPaymentsByFilter extends Job
{
    /**
     * @var Builder
     */
    protected $query;

    /** @var \Idma\Robokassa\Payment $robokassaPayment */
    protected $robokassa;

    protected $chunk = 250;

    protected $utimeout = 250;

    /**
     * @param Builder $query
     */
    public function __construct(Builder $query)
    {
        $this->query = clone $query;
        // orderBy нужен для чанка (без сортировки записи могут дублироваться)
        $this->query->orderBy('id');
        $this->robokassa = app('payment');
    }

    /**
     * @inheritdoc
     */
    public function handle()
    {
        $self = $this;
        $markedPayments = collect([]);

        $this->query->chunk($this->chunk, function (Collection $payments) use ($self, $markedPayments) {
            $robokassa = $self->robokassa;

            $payments
                ->where('is_paid', false, false)
                ->each(function (Payment $payment) use ($robokassa, $markedPayments) {
                    $result = $robokassa->getPaymentStatus($payment->getKey());
                    if ($result == $robokassa::PAYMENT_GOOD_DONE) {
                        dispatch(new MarkPaidPayment($payment));
                        $markedPayments->push($payment);
                    }
                });

            usleep($this->utimeout);
        });

        return $markedPayments;
    }
}