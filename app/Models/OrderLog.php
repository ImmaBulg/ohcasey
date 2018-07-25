<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Логи изменения заказа.
 * Делаем так - т.к. много "кастомных" логов,
 * типа - "объединили заказы".
 * Комментарии к таким логам задаются вручную.
 *
 * @property int $id
 * @property int $user_id
 * @property mixed $old_value
 * @property mixed $new_value
 * @property string $short_code
 * @property string $description
 * @property string $field_name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read User|null $user
 * @property-read Order $order
 * @package App\Models
 */
class OrderLog extends Model
{
    /**
     * код для фиксации обновления
     */
    const UPDATE_CODE = 'update';

    /**
     * код для фиксации создания
     */
    const CREATE_CODE = 'create';

    /**
     * код для фиксации удаления заказа
     */
    const DELETE_CODE = 'delete';

    /**
     * код для кастомных логов
     */
    const CUSTOM_CODE = 'custom';

    /** @inheritdoc */
    protected $fillable = [
        'user_id',
        'order_id',
        'short_code',
        'description',
        'field_name',
        'old_value',
        'new_value',
    ];

    /** @inheritdoc */
    protected static function boot()
    {
        parent::boot();
        static::saving(function (OrderLog $log) {
            if (! $log->user_id && \Auth::user()) {
                $log->user_id = \Auth::user()->id;
            }
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}