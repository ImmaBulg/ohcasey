<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Класс фона девайса.
 *
 * @property int $id
 * @property string $name
 * @property-read array bg_group
 * @property-read Collection|BackgroundGroup[] $backgroundGroups
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
class Background extends Model
{
    protected $fillable = ['name'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function (Background $background) {
            $background->backgroundGroups()->sync([]);

            return true;
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function backgroundGroups()
    {
        return $this->belongsToMany(BackgroundGroup::class, 'backgrounds_background_groups');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function product()
    {
        return $this->hasOne('App\Models\Shop\Product');
    }
}
