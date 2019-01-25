<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class TimeReport extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];
    public $timestamps = false;

    public function report()
    {
        return $this->belongsTo('App\Report');
    }
}
