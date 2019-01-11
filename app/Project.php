<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function user() 
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function status() 
    {
        return $this->belongsTo('App\Status');
    }

    public function tasks() 
    {
        return $this->hasMany('App\Task');
    }
}
