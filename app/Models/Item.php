<?php

namespace App\Models;

use App\Ohcasey\Ohcasey;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @property-read float $item_cost
 * @package App\Models
 */
class Item extends Model
{
    public $table = 'item';
    public $timestamps = false;
    protected $primaryKey = 'item_sku';
    protected $fillable = ['item_sku', 'item_name', 'item_cost'];

    /**
     * Get cost attribute
     * @return array
     */
    public function getItemCostAttribute()
    {
        /** @var Ohcasey $oh */
        $oh = app(Ohcasey::class);
        $group = $oh->getCurrentGroup();
        if ($group) {
            $groupItemPrice = ItemPrice::where([
                ['item_sku', '=', $this->item_sku],
                ['user_group', '=', $group]
            ])->first();
            if ($groupItemPrice) {
                return $groupItemPrice->item_cost;
            }
        }
        return $this->attributes['item_cost'];
    }

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'item_sku' => 'string',
    ];
}
