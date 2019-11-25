<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    public function location(){
        return $this->belongsTo('App\Location');
    }

    public function program_teaching(){
        return $this->hasMany('App\ProgramTeaching');
    }

    public function designation(){
        return $this->belongsTo('App\Designation');
    }
}
