<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Класс категории.
 * @property int $id
 * @property string $name
 * @property int $order
 * @property-read Collection|Background[] $backgrounds
 * @package App\Models
 */
class BackgroundGroup extends Model
{
    protected $fillable = ['name', 'order'];
    protected $casts = [
        'name'  => 'string',
        'order' => 'integer'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function backgrounds()
    {
        return $this->belongsToMany(Background::class, 'backgrounds_background_groups');
    }
}
