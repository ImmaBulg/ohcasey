<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'h1',
        'keywords',
        'description',
        'slug',
        'content'
    ];

    public static $filteredByText = [
        'title',
        'slug',
    ];
}