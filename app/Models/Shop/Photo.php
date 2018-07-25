<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Shop\Photos
 *
 * @property int $id
 * @property string $name
 * @property string $url -- мутатор
 * @property int $product_id
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Shop\Product $product
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Photo whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Photo whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Photo whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Photo whereProductId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Photo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Photo extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $appends = ['url'];

    const DEFAULT_TYPE = 'Product';

    /**
     * Fields can be fillable by user
     * @var array
     */
    protected $fillable = [
        'name',
        'photoable_id',
        'photoable_type',
    ];

    /**
     * Get all of the owning photoable models.
     */
    public function photoable()
    {
        return $this->morphTo();
    }

    /**
     * Получить URL до картинки.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return ('/' . config('product.photo.path') . $this->attributes['name']);
    }

    /**
     * Огриничить выборку для выбора основной фотографии (для первью картинок)
     *
     * @param $query
     * @return mixed
     */
    public function scopeMain($query)
    {
        return $query->orderBy('updated_at', 'desc')->first();
    }
}
