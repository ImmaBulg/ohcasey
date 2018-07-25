<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemPrice
 * @package App\Models
 */
class ItemPrice extends Model
{
    public $table = 'item_price';
    public $timestamps = false;
    protected $primaryKey = 'item_price_id';
    protected $fillable = ['user_group', 'item_sku', 'item_cost'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_group' => 'string',
        'item_sku'  => 'string',
        'item_cost' => 'float'
    ];
}
