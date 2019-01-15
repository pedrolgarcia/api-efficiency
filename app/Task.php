<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    public function status()
    {
        return $this->belongsTo('App\Status');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
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
