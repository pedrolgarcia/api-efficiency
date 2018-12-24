<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public function project() 
    {
        return $this->hasOne('App\Project');
    }

    public function task() 
    {
        return $this->hasOne('App\Task');
    }
}
