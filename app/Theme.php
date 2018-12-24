<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    public function setting() 
    {
        return $this->hasOne('App\Setting');
    }
}
