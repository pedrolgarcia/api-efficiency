<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    public function errorType()
    {
        return $this->hasOne('App\ErrorType');
    }

    public function errorReport()
    {
        return $this->belongsTo('App\ErrorReport');
    }
}
