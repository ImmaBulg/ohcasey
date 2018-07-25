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
 * @property bool $is_incoming
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|TransactionType[] $types
 * @method static TransactionCategory findOrFail(int $id)
 * @method static TransactionCategory first()
 * @package App\Models
 */
class TransactionCategory extends Model
{
    /**
     * @inheritdoc
     */
    protected $fillable = [
        'name',
        'is_incoming',
    ];

    /**
     * Является доходом.
     *
     * @return bool
     */
    public function isIncoming()
    {
        return (bool) $this->is_incoming;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function types()
    {
        return $this->hasMany(TransactionType::class);
    }
}