<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProgramTeaching extends Model
{
    public function staff(){
        return $this->belongsTo('App\Staff');
    }

    public function program(){
        return $this->belongsTo('App\Program');
    }
}
