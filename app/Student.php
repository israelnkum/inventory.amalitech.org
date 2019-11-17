<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    public function location(){
        return $this->belongsTo('App\Location');
    }

    public function program(){
        return $this->belongsTo('App\Program');
    }
    public function session(){
        return $this->belongsTo('App\Session');
    }
}
