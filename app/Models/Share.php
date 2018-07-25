<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Share
 * @property string $share_hash
 * @property string $share_source
 * @package App\Models
 */
class Share extends Model
{
    public $table = 'share';
    public $timestamps = false;
    protected $primaryKey = 'share_hash';
    protected $fillable = ['share_source'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'share_source' => 'array',
        'share_hash' => 'string',
    ];
}
