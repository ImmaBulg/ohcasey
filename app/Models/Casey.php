<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Casey
 * @property string $case_name
 * @property string $case_caption
 * @property string $case_description
 * @package App\Models
 */
class Casey extends Model
{
    public $table = 'casey';
    public $timestamps = false;
    protected $primaryKey = 'case_name';
    protected $fillable = ['case_name', 'case_caption', 'case_description'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'case_name' => 'string',
    ];
}
