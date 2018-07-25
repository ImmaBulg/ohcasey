<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class promotionCode
 * @package App\Models
 */
class PromotionCode extends Model
{
    public $table = 'promotion_code';
    public $timestamps = false;
    protected $dateFormat = 'Y-m-d H:m:sO';
    protected $primaryKey = 'code_id';
    protected $fillable = [
        'code_value',
        'code_enabled',
        'code_discount',
        'code_valid_from',
        'code_valid_till',
        'code_condition',
        'code_condition_params',
        'code_name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'code_id' => 'int',
        'code_value' => 'string',
        'code_enabled' => 'bool',
        'code_discount' => 'string',
        'code_valid_from' => 'date',
        'code_valid_till' => 'date',
        'code_condition' => 'array',
        'code_condition_params' => 'array',
        'code_name' => 'string'
    ];

    /**
     * Active attribute
     */
    public function getActiveAttribute()
    {
        return $this->isActive();
    }

    public function isActive()
    {
        if (!$this->code_enabled) {
            return false;
        }
        $now = Carbon::now();
        if ($this->code_valid_from && !$now->gt($this->code_valid_from)) {
            return false;
        }
        if ($this->code_valid_till && !$now->lt($this->code_valid_till)) {
            return false;
        }
        return true;
    }
}
