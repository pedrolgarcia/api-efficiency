<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    
    protected $fillable = [
        'name', 'email', 'password', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getJWTIdentifier() 
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims() 
    {
        return [];
    }

    public function projects() 
    {
        return $this->hasMany('App\Project');
    }

    public function setting() 
    {
        return $this->hasOne('App\Setting');
    }

    public function performance() 
    {
        return $this->hasOne('App\Performance');
    }
}
