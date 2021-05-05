<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasApiTokens;

    protected $appends = [
        'home_path',
    ];

    protected $casts = [
        'type_id' => 'integer',
    ];

    protected $fillable = [
        'name', 'email', 'password', 'type_id',
    ];

    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at',
    ];

    const TYPE_ADMIN = 1;
    const TYPE_NORMAL = 2;

    const TYPE_STRINGS = [
        1 => 'user_types.admin',
        2 => 'user_types.partner',
    ];

    public function isAdmin()
    {
        return $this->type_id === self::TYPE_ADMIN;
    }

    public function isNormal()
    {
        return $this->type_id === self::TYPE_NORMAL;
    }

    public function getHomePathAttribute()
    {
        switch ($this->type_id) {
            case 1:
            return 'home';
            default:
            return 'example'; // TODO change
        }
    }
}
