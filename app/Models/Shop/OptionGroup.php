<?php

namespace App\Models\Shop;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OptionGroup.
 * Представляет собой набор опций.
 * К примеру:
 * Товар-чехол, связан с OptionGroup, за которым закреплено 3 опции - телефон + цвет + тип чехла.
 * Товар-зарядка, связан с OptionGroup, за которым закреплена 1 опция - мощность.
 *
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property-read Option[]|Collection $options - набор опций этой группы
 * @property-read Product[]|Collection $products - товары этой группы

 * @package App\Models
 */
class OptionGroup extends Model
{
    const ID_CASE_GROUP = 1;

    use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];

    protected $fillable = [
        'name',
        'delivery_info'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function options()
    {
        return $this->belongsToMany(Option::class, 'option_option_group');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
    public function offers()
    {
        return $this->hasManyThrough('App\Models\Shop\Offer', 'App\Models\Shop\Product');
    }
}
