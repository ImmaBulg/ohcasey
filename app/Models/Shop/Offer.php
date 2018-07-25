<?php

namespace App\Models\Shop;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Offer.
 * Конкретное предложение товара, товар не может быть без предложений, в корзину падает именно предложение.
 *
 * @property int $id
 * @property int $product_id
 * @property int $quantity
 * @property int $weight
 * @property bool $active
 * @property array $options - содержит айдишники OptionValue.
 * @property-read Product $product
 * @property-read Collection $option_values - плохой мутатор
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @package App\Models
 */
class Offer extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'product_id',
        'quantity',
        'weight',
        'options',
        'active',
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // protected $appends = [
    //     'option_values',
    // ];

    protected $nullable = [
        'quantity',
        'weight',
    ];

    protected $hidden = ['pivot'];

    /**
     * boot Model methods
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->nullable as $field) {
                if (empty($model->$field) && $model->$field !== '0') {
                    $model->$field = null;
                }
            }
        });

    }

    // /**
    //  * @return Collection
    //  */
    // public function getOptionValuesAttribute()
    // {
    //     $valueQuery = OptionValue::query();
    //     $optionTable = with(new Option())->getTable();
    //     $optionValueTable = with(new OptionValue())->getTable();

    //     return $valueQuery
    //         ->join($optionTable, $optionValueTable . '.option_id', '=', $optionTable . '.id')
    //         ->whereIn($optionValueTable . '.id', $this->options)
    //         ->orderBy($optionTable . '.order')
    //         ->get()
    //         ->pluck('title');
    // }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
    public function optionGroup()
    {
        return $this->hasManyThrough(OptionGroup::class, Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function optionValues()
    {
        return $this->belongsToMany(OptionValue::class);
    }

    public function setOptionsAttribute($options)
    {
        $this->optionValues()->sync($options);
    }
}