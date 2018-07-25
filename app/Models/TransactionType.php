<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель тип транзакции
 *
 * @property int $id
 * @property string $name
 * @property int $transaction_category_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|Transaction[] $transactions
 * @property-read TransactionCategory $category
 * @method static TransactionType findOrFail(int $id)
 * @method static TransactionType first()
 * @package App\Models
 */
class TransactionType extends Model
{
    // Оплаты онлайн
    const ID_TYPE_FOR_INCOMING_BY_PAYMENT = 1;

    // Оплаты наличными
    const ID_TYPE_FOR_INCOMING_BY_ORDER   = 2;

    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'transaction_category_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(TransactionCategory::class, 'transaction_category_id');
    }
}