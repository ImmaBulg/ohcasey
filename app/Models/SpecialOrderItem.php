<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Сопутствующий товар заказа.
 *
 * @property string $name
 * @property float $price
 * @property int $order_id
 * @property-read Order $order
 * @package App\Models
 */
class SpecialOrderItem extends Model
{
    protected $fillable = [
        'name',
        'price',
        'order_id'
    ];

    /**
     * @param mixed $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value ?: 0;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
