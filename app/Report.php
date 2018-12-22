<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
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
