<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * @property int $city_id
 * @property string $city_name
 * @property string $city_name_full
 *
 * @property-read Collection|DeliveryCdek[] $deliveriesCdek
 * @package App\Models
 */
class CdekCity extends Model
{
    public $table = 'cdek_city';
    public $timestamps = false;
    protected $primaryKey = 'city_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function deliveriesCdek()
    {
        return $this->hasMany(DeliveryCdek::class, 'cdek_city_id');
    }
}
