<?php

namespace App\Models\Shop;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class OptionValue.
 * Возможные значения для каждого свойства.
 *
 * @property int $id
 * @property int $option_id
 * @property int $order
 * @property string $value
 * @property string $title
 * @property string $description
 * @property string $image
 * @property Carbon $deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property-read Option $option
 *
 * @package App\Models
 */
class OptionValue extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'option_id',
        'title',
        'value',
        'description',
        'order',
        'image'
    ];

    protected $hidden = ['pivot'];

    public static $filteredByText = [
        'title',
        'value',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }
}
