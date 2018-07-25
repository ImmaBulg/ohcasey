<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Delivery
 * @package App\Models
 */
class Delivery extends Model
{
    public $table = 'delivery';
    public $timestamps = false;
    protected $primaryKey = 'delivery_name';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'delivery_name' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment_methods()
    {
        return $this->belongsToMany('App\Models\PaymentMethod', 'delivery_payment_method', 'delivery_name', 'payment_methods_id');
    }
}
