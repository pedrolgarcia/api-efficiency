<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErrorReport extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    public $timestamps = false;

    public function errors()
    {
        return $this->hasMany('App\Error');
    }

    public function report()
    {
        return $this->belongsTo('App\Report');
    }
}