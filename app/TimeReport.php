<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeReport extends Model
{
    public function report()
    {
        return $this->belongTo('App\Report');
    }
}
