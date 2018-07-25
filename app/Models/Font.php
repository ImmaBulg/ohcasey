<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Font
 * @package App\Models
 */
class Font extends Model
{
    public $table = 'font';
    public $timestamps = false;
    protected $primaryKey = 'font_name';
    protected $fillable = ['font_name', 'font_caption', 'font_enabled'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'font_name' => 'string',
    ];
}
