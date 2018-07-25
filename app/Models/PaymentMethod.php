<?php

namespace App\Models;

use Doctrine\Common\Collections\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentMethod
 * @property int $id
 * @property bool $is_online
 * @property string $name
 * @property-read Collection|Delivery[] $deliveries
 * @package App\Models
 */
class PaymentMethod extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deliveries()
    {
        return $this->belongsToMany(Delivery::class, 'delivery_payment_method', 'payment_methods_id', 'delivery_name');
    }

    /**
     * @return bool
     */
    public function getIsOnlineAttribute()
    {
        return $this->id == config('payment.online_payment_method');
    }
}