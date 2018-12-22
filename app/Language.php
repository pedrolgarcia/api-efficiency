<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    public function setting()
    {
        return $this->belongsTo('App\Setting');
    }
}
