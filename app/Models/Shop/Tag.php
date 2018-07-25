<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public static $filteredByText = [
        'name',
    ];
    
    protected $fillable = [
        'name',
        'title',
        'h1',
        'slug',
        'display_price',
        'large_photos',
        'order',
        'description',
        'keywords',
    ];
    
    protected $nullable = [
        'title',
    ];
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag', 'tag_id', 'product_id');
    }
}
