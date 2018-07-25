<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class MenuLink
 * @property int $id
 * @property string $name
 * @property string $url
 * @property int|null $parent_id
 * @property int $sort
 * @property boolean $information_type
 * @property int $display_type
 * @property-read MenuLink[]|Collection $children
 * @property-read MenuLink|null $parent
 * @package App\Models
 */
class MenuLink extends Model
{
    const DEFAULT_SORT = 500;
    const GLOBAL_CACHE_KEY = 'global_menu';

    protected $fillable = [
        'name',
        'url',
        'parent_id',
        'sort',
        'information_type',
        'display_type'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function (MenuLink $link) {
            $link->sort = $link->sort ?: static::DEFAULT_SORT;
        });
        static::saved(function (MenuLink $link) {
            \Cache::forget(static::GLOBAL_CACHE_KEY);
        });
        static::deleted(function (MenuLink $link) {
            \Cache::forget(static::GLOBAL_CACHE_KEY);
        });
    }

    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = (is_numeric($value) ? $value : null);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
}
