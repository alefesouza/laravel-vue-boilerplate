<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'type_id'
    ];

    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at',
    ];

    public function isAdmin()
    {
        return $this->type_id == 1;
    }

    public function isNormal()
    {
        return $this->type_id == 2;
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

    public function getHomePath()
    {
        switch ($this->type_id) {
            case 1:
            return '/';
            default:
            return '/example'; // TODO change
        }
    }
}
