<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    public function student(){
        return $this->hasMany('App\Student');
    }

    public function staff(){
        return $this->hasMany('App\Staff');
    }
}
