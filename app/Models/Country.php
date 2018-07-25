<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * @package App\Models
 */
class Country extends Model
{
    public $table = 'country';
    public $timestamps = false;
    protected $primaryKey = 'country_iso';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'country_iso' => 'string',
    ];
}
