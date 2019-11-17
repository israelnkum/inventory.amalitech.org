<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public function student(){
        return $this->hasMany('App\Student');
    }
    public function program_teaching(){
        return $this->belongsTo('App\ProgramTeaching');
    }
}
