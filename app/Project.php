<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function user() 
    {
        return $this->belongsTo('App\User');
    }

    public function status() 
    {
        return $this->belongTo('App\Status');
    }

    public function tasks() 
    {
        return $this->hasMany('App\Task');
    }
}
