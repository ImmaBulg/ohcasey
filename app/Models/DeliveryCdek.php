<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DeliveryCdek
 * @property int $order_id
 * @property string $cdek_type
 * @property string $cdek_pvz
 * @property string $cdek_pvz_name
 * @property string $cdek_pvz_address
 * @property string $cdek_pvz_worktime
 * @property int $cdek_city_id
 *
 * @property Order $order
 * @property CdekCity $cdekCity
 *
 * @package App\Models
 */
class DeliveryCdek extends Model
{
    public $table = 'delivery_cdek';
    public $timestamps = false;
    protected $primaryKey = 'order_id';
    protected $fillable = ['order_id', 'cdek_type', 'cdek_pvz', 'cdek_pvz_name', 'cdek_pvz_address', 'cdek_pvz_worktime'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cdekCity()
    {
        return $this->belongsTo(CdekCity::class, 'cdek_city_id');
    }
}
