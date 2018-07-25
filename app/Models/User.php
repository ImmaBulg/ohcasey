<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Класс пользователя
 * @property int $id
 * @property string $name
 * @property string $login
 * @property string $password
 * @property string $remember_token
 * @property boolean $superadmin
 * @property-read Collection|OrderLog[] $orderLogs
 * @package App\Models
 */
class User extends Authenticatable
{
    public $table = 'user';
    public $timestamps = false;
    protected $fillable = [
        'name', 'login', 'password', 'superadmin', 'role'
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderLogs()
    {
        return $this->hasMany(OrderLog::class);
    }
}
