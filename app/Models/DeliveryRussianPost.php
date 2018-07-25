<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DeliveryRussianPost
 * @property int $order_id
 * @property string $post_code
 * @property-read Order $order
 * @package App\Models
 */
class DeliveryRussianPost extends Model
{
    public $table = 'delivery_russian_post';
    public $timestamps = false;
    protected $primaryKey = 'order_id';
    protected $fillable = ['order_id', 'post_code'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
