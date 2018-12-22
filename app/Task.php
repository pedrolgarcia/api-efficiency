<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    public function status()
    {
        return $this->hasOne('App\Status');
    }

    public function category()
    {
        return $this->hasOne('App\Category');
    }

    public function project()
    {
        return $this->belongsTo('App\Project');
    }

    public function reports()
    {
        return $this->hasMany('App\Report');
    }
}
