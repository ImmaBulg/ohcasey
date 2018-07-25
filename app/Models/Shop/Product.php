<?php

namespace App\Models\Shop;

use DB;
use App\Models\Background;
use App\Models\Device;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Shop\Product
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property string $code
 * @property float $price
 * @property float $discount
 * @property bool $active
 * @property bool $sale
 * @property bool $hit
 * @property int $draft_user_id
 * @property int $background_id
 * @property int $option_group_id
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Shop\Category[] $categories
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Shop\Product[] $related
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Shop\Tag[] $tags
 * @property-read mixed $price_string
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Shop\Photo[] $photos
 * @property-read Background $background
 * @property-read OptionGroup $optionGroup
 * @property-read Collection|Offers[] $offers
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product draft()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereDiscount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereDraftUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product wherePrice($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Shop\Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Product extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name',
        'background_id',
        'title',
        'h1',
        'keywords',
        'description',
        'code',
        'price',
        'discount',
        'order',
        'active',
        'categories',
        'tags',
        'related',
        'bestseller',
        'maket_photo',
        'option_group_id',
        'hit',
        'sale',
        'htmlDescription'
    ];

    protected $appends = [
        'price_string',
        'main_photo_url'
    ];

    public static $filteredByText = [
        'name',
        'code',
    ];

    protected $nullable = [
        'name',
        'background_id',
        'title',
        'description',
        'discount',
        'draft_user_id',
    ];

    /**
     * boot Model methods
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->nullable as $field) {
                if (empty($model->$field) && $model->$field !== '0') {
                    $model->$field = null;
                }
            }
        });

        static::addGlobalScope('draft', function (Builder $builder) {
            $builder->whereNull('draft_user_id');
        });

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function firstCategory()
    {
        return $this->categories()->orderBy('order')->active()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable')->orderBy('updated_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function background()
    {
        return $this->belongsTo(Background::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function optionGroup()
    {
        return $this->belongsTo(OptionGroup::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function related()
	{
        return $this->belongsToMany(Product::class,'product_related','product_id','related_product_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id');
    }

    /**
     * @return string
     */
    public function getPriceStringAttribute()
    {
        if (!empty($this->attributes['price'])) {
            return number_format($this->attributes['price'], 2).html_entity_decode("&nbsp;&#8381;");
        }
    }

    /**
     * @param Array
     */
    public function setCategoriesAttribute($cats)
    {
        $this->categories()->sync($cats);
    }

    /**
     * @param Array
     */
    public function setRelatedAttribute($products)
    {
		$this->related()->sync($products);
    }
    
    /**
     * @param Array
     */
    public function setTagsAttribute($tags)
    {
		$this->tags()->sync($tags);
    }

    /**
     * Draft for current user
     * @param $query
     * @return $query
     */
    public function scopeDraft($query)
    {
        return $query->withoutGlobalScope('draft')->where('draft_user_id', \Auth::id());
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

    /**
     * hasOffer scope
     * @param $query
     * @return $query
     */
    public function scopeHasOffer($query)
    {
        return $query->has('offers');
    }

    /**
     * @return $query
     */
    public function scopeFilter($query, $params)
    {
        $query = $query->with('background', 'photos')
        ->active()
        ->hasOffer();
        
//        $query->whereHas('offers', function ($query) {
//            $query->whereHas('optionValues', function ($query) {
//                $query->where('value', 'yellow');
//            });
//
//            $query->whereHas('optionValues', function ($query) {
//                $query->where('value', 'iphone5c');
//            });
//
//            $query->whereHas('optionValues', function ($query) {
//                $query->where('value', 'silicone');
//            });
//        });
        
        if($params['sort'] == 'popular'){
            
            if($params['device'] and $params['case'] and $params['color']){
                
                $device_colors = Device::where('device_name', $params['device'])->value('device_colors');
                
                if(isset($device_colors[$params['color']])){
                    
                    $query->select('*');
                    
                    $sub = DB::table('offers')->select('offers.order_count')
                        ->leftJoin('offer_option_value AS oov1','oov1.offer_id','=','offers.id')    
                        ->leftJoin('option_values AS ov1','ov1.id','=','oov1.option_value_id')    
                        ->leftJoin('offer_option_value AS oov2','oov2.offer_id','=','offers.id')    
                        ->leftJoin('option_values AS ov2','ov2.id','=','oov2.option_value_id')    
                        ->leftJoin('offer_option_value AS oov3','oov3.offer_id','=','offers.id')    
                        ->leftJoin('option_values AS ov3','ov3.id','=','oov3.option_value_id')    
                        ->where('offers.product_id', DB::raw('products.id'))
                        ->where('ov1.value', '=' , $params['device'])
                        ->where('ov2.value', '=' , $params['case'])
                        ->where('ov3.value', '=' , $device_colors[$params['color']])
                        ->take(1);
                    
                    $query->selectSub($sub,'order_count');
                    
                    $query = $query->orderBy('order_count','desc');
                    
                }else{
                   $query = $query->orderBy('view_count','desc'); 
                }
                
            }else{
                $query = $query->orderBy('view_count','desc');
            }
            
        }else{
            $query = $query->orderBy('order');
        }
        
        return $query;
    }

    /**
     * @param  string  $device
     * @param  string  $case
     * @param  integer $color
     * @return string
     */
    public function mainPhoto($device = 'iphone7', $case = 'silicone', $color = 0)
    {
        if (isset($this->background->name) && $this->option_group_id == OptionGroup::ID_CASE_GROUP && $this->maket_photo) {
            return route('api.product.image', [
                'bgName' => $this->background->name,
                'deviceName' => $device,
                'caseFileName' => $case,
                'deviceColorIndex' => $color,
            ]);
        } else {
            /** @var Photo $photo */
            $photo = $this->photos->first();

            if ($photo) {
                return $photo->url;
            }
        }

        return null;
    }

    public function getMainPhotoUrlAttribute()
    {

        $device = request()->input('device', 'iphone7');
        $case = request()->input('case', 'silicone');
        $color = request()->input('color', 0);

        return $this->mainPhoto($device, $case, $color);
    }
}