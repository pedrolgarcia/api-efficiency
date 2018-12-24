<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
