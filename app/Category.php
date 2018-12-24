<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function task()
    {
        return $this->hasMany('App\Task');
    }
}
