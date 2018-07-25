<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SmileGroup
 * @package App\Models
 */
class SmileGroup extends Model
{
    public $table = 'smile_group';
    public $timestamps = false;
    protected $primaryKey = 'smile_group_name';
    protected $fillable = ['smile_group_name'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'smile_group_name' => 'string',
    ];
}
