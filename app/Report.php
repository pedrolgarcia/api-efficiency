<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    public function errorReport()
    {
        return $this->hasOne('App\ErrorReport');
    }

    public function annotation()
    {
        return $this->hasOne('App\Annotation');
    }

    public function timeReport()
    {
        return $this->hasOne('App\TimeReport');
    }
}
