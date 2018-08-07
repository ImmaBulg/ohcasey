<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


/**
 * @property  h1
 * @property title
 * @property keywords
 * @property desc
 * @property  textup
 * @property textdown
 */
class CatMeta extends Model
{
    public $table = 'cat_meta';

    protected $fillable = [
        'cat_id',
        'phone',
        'h1',
        'title',
        'keywords',
        'desc',
        'textup',
        'textdown'
    ];

    public function __construct(array $attributes = []) {
        parent::__construct();
    }

    public function setMeta($meta){
        $this->h1 = $meta['h1'];
        $this->title = $meta['title'];
        $this->desc = $meta['desc'];
        $this->keywords = $meta['keywords'];
        $this->textup = $meta['textup'];
        $this->textdown = $meta['textdown'];
    }
}