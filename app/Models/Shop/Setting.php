<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $primaryKey = 'key';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'key',
        'value',
        'title',
        'type',
        'group',
    ];
}
