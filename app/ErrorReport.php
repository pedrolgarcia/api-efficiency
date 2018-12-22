<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorReport extends Model
{
    public function errors()
    {
        return $this->hasMany('App\Error');
    }

    public function report()
    {
        return $this->belongsTo('App\Report');
    }
}
