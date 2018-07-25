<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Class Cart
 *
 * @property int $cart_id
 * @property Carbon $cart_ts
 * @property int $cart_ip
 * @property int $cart_order_id
 * @property string $cart_user_agent
 * @property string $promotion_code_id
 * @property float $delivery_amount
 * @property string $delivery_name
 * @property-red stdObject $summary - на поле стоит мутатор, осторожней
 * @property-read Order|null $order
 * @property-read Collection|CartSet[] $cartSet
 * @property-read Collection|CartSetCase[] $cartSetCase
 * @property-read Collection|CartSetProduct[] $cartSetProducts
 * @property-read PromotionCode|null $promotionCode
 * @package App\Models
 */
class Cart extends Model
{
    public $table = 'cart';
    public $timestamps = false;
    protected $dates = ['cart_ts'];
    protected $primaryKey = 'cart_id';
    protected $fillable = ['cart_ip', 'cart_user_agent', 'promotion_code_id', 'cart_order_id', 'delivery_amount', 'delivery_name'];
    protected $dateFormat = 'Y-m-d H:i:sT';


    /**
     * @param Builder $query
     * @param Carbon $dateStart
     * @param Carbon $dateEnd
     * @return Builder
     */
    public function scopeBetweenDates(Builder $query, Carbon $dateStart, Carbon $dateEnd)
    {
        return $query->whereBetween('cart_ts', [$dateStart, $dateEnd]);
    }

    /**
     * @return mixed
     */
    public function getSummaryAttribute()
    {
        $result = array_first(DB::select('
            select
                coalesce(sum(cs.item_cost * cs.item_count), 0) as subtotal,
                sum(cs.item_count) as cnt
            from cart c
                inner join cart_set cs on cs.cart_id = c.cart_id
            where c.cart_id = :cart_id
            ', ['cart_id' => $this->cart_id]));

        $result->amount = $result->subtotal + $this->delivery_amount;

        return $result;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartSet()
    {
        return $this->hasMany(CartSet::class, 'cart_id', 'cart_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartSetProducts()
    {
        return $this->hasMany(CartSetProduct::class, 'cart_id', 'cart_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartSetCase()
    {
        return $this->hasMany(CartSetCase::class, 'cart_id', 'cart_id')->with(['device','offer.product']);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function promotionCode()
    {
        return $this->hasOne(PromotionCode::class, 'code_id', 'promotion_code_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function order()
    {
        return $this->hasOne(Order::class, 'order_id', 'cart_order_id');
    }
}
