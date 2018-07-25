<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Smile
 * @package App\Models
 */
class Smile extends Model
{
    public $table = 'smile';
    public $timestamps = false;
    protected $primaryKey = 'smile_name';
    protected $fillable = ['smile_name', 'smile_group'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'smile_name' => 'string',
        'smile_group' => 'array',
    ];
}
