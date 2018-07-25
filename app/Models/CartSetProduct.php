<?php

namespace App\Models;
use App\Models\Shop\Offer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class CartSetProduct
 *
 * @property int $id
 * @property int $cart_id
 * @property int $item_count
 * @property int $offer_id
 * @property int $cart_set_id
 * @property string $item_sku
 * @property float $item_cost
 * @property-read Cart $cart
 * @property-read Offer $offer
 * @property-read CartSet $cartSet
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
 */
class CartSetProduct extends Model
{
    public $table = 'cart_set_product';

    protected $fillable = [
        'cart_id',
        'item_count',
        'item_cost',
        'item_sku',
        'offer_id',
        'cart_set_id',
        'size',
        'print',
        'date_send',
        'supposed_date',
        'date_back',
        'print_status_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cartSet()
    {
        return $this->belongsTo(CartSet::class);
    }
}
