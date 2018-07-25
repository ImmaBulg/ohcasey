<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель оплат заказа.
 *
 * @property int $id
 * @property int $order_id
 * @property string $hash
 * @property float $amount
 * @property bool $is_paid
 * @property Carbon|null $paid_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Order $order
 * @package App\Models
 */
class Payment extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function (Payment $payment) {
            do {
                $hash = str_random(16);
            } while (Payment::where('hash', $hash)->first());
            $payment->hash = $hash;
        });
    }

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'order_id',
        'amount'
    ];

    /**
     * @inheritdoc
     */
    protected $dates = [
        'paid_date'
    ];

    /**
     * Является оплаченым.
     *
     * @return bool
     */
    public function isPaid()
    {
        return (bool) $this->is_paid;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id')->withTrashed();
    }
}