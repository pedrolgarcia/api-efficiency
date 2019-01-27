<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Error extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    public $timestamps = false;

    public function errorType()
    {
        return $this->belongsTo('App\ErrorType');
    }

    public function errorReport()
    {
        return $this->belongsTo('App\ErrorReport');
    }
}
