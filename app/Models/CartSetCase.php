<?php

namespace App\Models;
use App\Models\Shop\Product;

/**
 * Class CartSetCase
 * @property string $device_name
 * @property string $case_name
 * @property array $item_source
 * @property int $offer_id
 * @property-read Device $device
 * @package App\Models
 */
class CartSetCase extends CartSet
{
    public $table = 'cart_set_case';
    protected $fillable = ['item_sku', 'item_count', 'item_cost', 'device_name', 'case_name', 'item_source', 'offer_id', 'date_send', 'date_supposed', 'date_back', 'print_status_id'];
    protected $casts = ['item_source' => 'array'];
    
    protected $appends = [
        'product',
    ];

    /**
     * Device
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function device()
    {
        return $this->hasOne(Device::class, 'device_name', 'device_name');
    }

    /**
     * Casey
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function casey()
    {
        return $this->hasOne(Casey::class, 'case_name', 'case_name');
    }
	
    /**
     * Offer
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function offer()
    {
        return $this->belongsTo(Shop\Offer::class);
    }
    
    public function getProductAttribute()
    {
        if (!empty($this->attributes['offer_id'])) {
            return $this->offer->product;
        }
        
        if(isset($this->item_source['DEVICE']['bg']) and $this->item_source['DEVICE']['bg']){

            $background = $this->item_source['DEVICE']['bg'];
                                    
            return Product::whereHas('background', function ($query) use ($background) {
                            $query->where('name', $background);
                        })->first();
        }
    }
}
