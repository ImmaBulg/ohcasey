<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CartSet
 * @property int $cart_id
 * @property-read Cart $cart
 * @property int $cart_set_id
 * @property int $item_count
 * @property int $item_cost
 * @property string $item_sku
 * @property string $device_name
 * @property string $case_name
 * @property array $item_source
 * @property-read Device $device
 * @property-read Casey $casey
 * @package App\Models
 */
class CartSet extends Model
{
    public $table = 'cart_set';
    public $timestamps = false;
    protected $primaryKey = 'cart_set_id';
    protected $fillable = ['item_sku', 'item_count', 'item_cost'];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function ($cartSetCase) {
            if ($cartSetCase instanceof CartSetCase) {
                if (\Storage::exists($cartSetCase->getOrderImgPath())) {
                    \Storage::delete($cartSetCase->getOrderImgPath());
                }
            }
        });
    }

    public function getOrderImgPath()
    {
        if ($order = data_get($this, 'cart.order')) {
            $date = new Carbon($order->order_ts, 'Europe/Moscow');
            return 'orders/' . $date->format('Y/m/d') . '/' . $order->order_id . '/img/item_' . $this->cart_set_id . '.png';
        }
    }

    /**
     * Get case
     */
    public function cartSetCase()
    {
        return $this->hasOne(CartSetCase::class, 'cart_set_id', 'cart_set_id');
    }

    /**
     * Get product
     */
    public function cartSetProduct()
    {
        return $this->hasOne(CartSetProduct::class, 'cart_set_id', 'cart_set_id');
    }

    /**
     * Cart item
     */
    public function item()
    {
        return $this->hasOne(Item::class, 'item_sku', 'item_sku');
    }

    /**
     * Cart
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'cart_id');
    }
}
