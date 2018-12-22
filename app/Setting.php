<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public function language()
    {
        return $this->hasOne('App\Language');
    }

    public function user()
    {
        return $this->hasOne('App\User');
    }

    public function theme()
    {
        return $this->hasOne('App\Theme');
    }
}
