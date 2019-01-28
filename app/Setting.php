<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];
    
    public function language()
    {
        return $this->belongsTo('App\Language');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function theme()
    {
        return $this->belongsTo('App\Theme');
    }
}
