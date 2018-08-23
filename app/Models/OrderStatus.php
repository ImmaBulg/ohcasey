<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OrderStatus
 *
 * @property int $status_id
 * @property int $sort
 * @property string $status_name
 * @property string $status_color
 * @property bool $status_success
 * @property Carbon|null $deleted_at
 * @property-read Collection|Order[] $orders
 * @package App\Models
 */
class OrderStatus extends Model
{
    use SoftDeletes;

    const STATUS_ID_NEW               = 0;
    const STATUS_ID_WAIT_PAYMENT      = 9;
    const STATUS_ID_DESIGNING         = 8;
    const STATUS_ID_PAID              = 1;
    const STATUS_ID_IN_PRINT          = 3;
    const STATUS_ID_PRINT_WITH_DEFECT = 10;
    const STATUS_ID_PRINT_PICKUP      = 11;
    const STATUS_ID_PRINT_SDEK        = 12;
    const STATUS_ID_PRINT_COURIER     = 13;
    const STATUS_ID_NO_CONNECTION     = 5;
    const STATUS_ID_THINKS            = 14;
    const STATUS_ID_FINISHED          = 6;
    const STATUS_ID_CANCELED          = 7;
    const STATUS_PRINTED              = 4;

    public $table      = 'order_status';
    public $timestamps = false;

    protected $primaryKey = 'status_id';
    protected $dates      = ['deleted_at'];
    protected $casts      = ['status_success' => 'boolean'];
    protected $fillable   = ['status_name', 'status_color', 'status_success', 'sort'];

    /**
     * Кастует $value в int|null.
     *
     * @param mixed $value
     */
    public function setSortAttribute($value)
    {
        $this->attributes['sort'] = (is_numeric($value) ? intval($value) : null);
    }

    /**
     * Кастует $value в булево.
     *
     * @param mixed $value
     */
    public function setStatusSuccessAttribute($value)
    {
        $this->attributes['status_success'] = (bool) $value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status_id', 'status_id');
    }
}
