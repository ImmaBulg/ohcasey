<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель транзакции (не связана с онлайн оплатой).
 *
 * @property int $id
 * @property int $order_id
 * @property int $transaction_type_id
 * @property int $payment_id
 * @property string $comment
 * @property float $amount
 * @property Carbon $datetime
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Transaction $type - тип транзакции
 * @property-read Order|null $order - транзакция по заказу (наличная)
 * @property-read Payment|null $payment - транзация по онлайн оплате
 * @method static Transaction findOrFail(int $id)
 * @method static Transaction first()
 * @package App\Models
 */
class Transaction extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'order_id',
        'amount',
        'transaction_type_id',
        'payment_id',
        'datetime',
        'comment'
    ];

    /**
     * @inheritdoc
     */
    protected $dates = [
        'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (Transaction $transaction) {
            if (! $transaction->datetime) {
                $transaction->datetime = Carbon::now();
            }

            return true;
        });
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}