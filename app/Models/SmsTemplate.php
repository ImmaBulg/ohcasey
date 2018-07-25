<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SmsTemplate
 * @property int $id
 * @property string $name
 * @property string $template
 * @property int $before_order_status_id
 * @property int $after_order_status_id
 * @property OrderStatus|null $beforeOrderStatus
 * @property OrderStatus $afterOrderStatus
 * @package App\Models
 */
class SmsTemplate extends Model
{
    protected $fillable = [
        'name',
        'template',
        'before_order_status_id',
        'after_order_status_id'
    ];

    protected $casts = [
        'after_order_status_id' => 'integer',
    ];

    /**
     * @param Order $order
     * @return string
     */
    public function render(Order $order)
    {
        return str_replace("#ORDER_CODE#", $order->order_id, $this->template);
    }

    public function setBeforeOrderStatusIdAttribute($value)
    {
        $this->attributes['before_order_status_id'] = (is_numeric($value) ? $value : null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function beforeOrderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'before_order_status_id', 'status_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function afterOrderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'after_order_status_id', 'status_id');
    }
}
