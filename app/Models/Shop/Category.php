<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Shop\Category
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property int $parent
 * @property string $slug
 * @property string $image
 * @property int $order
 * @property boolean $display_price
 * @property boolean $active
 * @property \Carbon\Carbon $published_at
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read string $path
 * @property-read \App\Models\Shop\Category $selfParent
 * @property-read \App\Models\Shop\Category[]|Collection $selfChildren
 * @property-read \App\Models\Shop\Product[]|Collection $products
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereParent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereDisplayPrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category wherePublishedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Category whereCatalogDisplayType($value)
 * @mixin \Eloquent
 */
class Category extends Model
{
    use SoftDeletes, \App\Models\Tree;

    public $timestamps = true;

    protected $dates = ['deleted_at', 'published_at'];

    /**
     * Max iteration to buil path category string
     * @var integer
     */
    protected $maxFindParent = 10;

    /**
     * Separator symbols in category path string
     * @var string
     */
    protected $separator = '&nbsp;>&nbsp;';

    /**
     * Fields can be fillable by user
     * @var array
     */
    protected $fillable = [
        'name',
        'title',
        'slug',
        'description',
        'keywords',
        'h1',
        'h2',
        'text_top',
        'text_bottom',
        'parent',
        'order',
        'display_price',
        'published_at',
        'large_photos',
        'catalog_display_type',
        'active',
    ];

    protected $hidden = [
        'pivot'
    ];

    protected $nullable = [
        'description',
        'keywords',
        'image',
        'banner_image',
        'published_at',
    ];

    protected $appends = ['path'];

    protected $casts = [
       'large_photos' => 'boolean',
       'catalog_display_type' => 'boolean',
       'display_price' => 'boolean',
       'active' => 'boolean',
    ];

    protected static function boot() {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->nullable as $field) {
                if(empty($model->$field) && $model->$field !== '0') {
                    $model->$field = null;
                }
            }
            foreach ($model->casts as $k => $v) {
                if($v == 'boolean') {
                    $model->$k = (boolean)$model->$k;
                }
            }
        });
    }

    /**
     * @return string $path
     */
    public function getPathAttribute()
    {
        $maxFindParent = $this->maxFindParent;
        $path = $this->name;
        $parent = $this->selfParent;
        while ($parent && $maxFindParent) {
            $path = $parent->name.$this->separator.$path;
            $parent = $parent->selfParent;
            $maxFindParent--;
        }

        return $path;
    }

    /**
     * @return string $url
     */
    public function getUrlAttribute()
    {
        $slugs = [$this->slug];

        if ($this->selfParent) {
            do {
                $parent = $this->selfParent;
                array_unshift($slugs, $parent->slug);
            } while ($parent->selfParent);
        }

        return join($slugs, '/');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function selfParent()
    {
        return $this->belongsTo(Category::class, 'parent')->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function selfChildren()
    {
        return $this->hasMany(Category::class, 'parent')->orderBy('order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function childrenProducts()
    {
        return $this->hasManyThrough(Product::class, Category::class, 'parent', 'id', 'id');
    }

    /**
     * List of categories with Path from root to cuurent
     * @param $query
     * @return $query
     */
    public function scopePathList($query)
    {
        return $query->select('id', 'name', 'parent')->with('selfParent')->orderBy('order');
    }

    /**
     * @param mixed
     */
    public function setParentAttribute($value)
    {
        if($value == 0 || $value == 'null'){
            $this->attributes['parent'] = null;
        }
        else{
            $this->attributes['parent'] = $value;
        }

    }

    /**
     * @param mixed
     */
    public function setPublishedAtAttribute($value)
    {
        if(!empty($value)){
            $this->attributes['published_at'] = date('Y-m-d H:i:s', strtotime($value));
        }
        else {
            $this->attributes['published_at'] = null;
        }
    }

    /**
     * @return string
     */
    public function getImageAttribute()
    {
        if(!empty($this->attributes['image'])){
            return '/'.config('category.image_path').$this->attributes['image'];
        }
    }

    /**
     * @return string
     */
    public function getBannerImageAttribute()
    {
        if(!empty($this->attributes['banner_image'])){
            return '/'.config('category.image_path').$this->attributes['banner_image'];
        }
    }

    /**
     * Active scope
     * @param $query
     * @return $query
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

}
