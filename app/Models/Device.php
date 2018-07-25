<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Device
 * @property string  $device_name
 * @property string  $device_caption
 * @property boolean $device_enabled
 * @property array   $device_colors
 * @property array   $device_cases
 * @property int     $device_order
 * @package App\Models
 */
class Device extends Model
{
    public $table = 'device';
    public $timestamps = false;
    protected $primaryKey = 'device_name';
    protected $fillable = ['device_name', 'device_caption', 'device_enabled', 'device_colors', 'device_cases', 'device_order'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'device_name' => 'string',
        'device_colors' => 'array',
        'device_cases' => 'array',
    ];

    /**
     * Get mask attribute
     * @return array
     */
    public function getMaskAttribute()
    {
        $path = storage_path('app/device/'.$this->device_name);
        $mask = [];
        $files = scandir($path);
        foreach ($files as $file) {
            if (preg_match('/mask\_(.+)\.png/', $file, $matches)) {
                $mask[] = $matches[1];
            }
        }
        return $mask;
    }

    /**
     * Get icon
     */
    public function getIconAttribute()
    {
        $path = storage_path('app/device/'.$this->device_name);
        $files = scandir($path);
        foreach ($files as $file) {
            if (preg_match('/icon(.+)/', $file, $matches)) {
                return $file;
            }
        }
        return 'icon.png';
    }
}
