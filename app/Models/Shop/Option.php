<?php

namespace App\Models\Shop;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Option
 * @property int $id
 * @property string $key
 * @property string $name
 * @property int $order
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property-read OptionValue[]|Collection $values
 * @property-read OptionGroup[]|Collection $groups
 * @package App\Models
 */
class Option extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'key',
        'name',
        'order',
    ];

    protected $hidden = ['pivot'];

    public static $filteredByText = [
        'name',
        'key',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany(OptionValue::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Option::class, 'option_option_group');
    }
}