<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorType extends Model
{
    public function error()
    {
        return $this->hasMany('App\Error');
    }
}
