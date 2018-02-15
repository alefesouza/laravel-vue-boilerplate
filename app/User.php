<?php

namespace App;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

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

    public function isAdmin()
    {
        return $this->type_id === 1;
    }

    public function isNormal()
    {
        return $this->type_id === 2;
    }

    public function getTypeText()
    {
        switch ($this->type_id) {
            case 1:
            return __('strings.admin');
            case 2:
            return __('strings.normal');
        }
    }

    public function getHomePathAttribute()
    {
        switch ($this->type_id) {
            case 1:
            return '/';
            default:
            return '/example'; // TODO change
        }
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
