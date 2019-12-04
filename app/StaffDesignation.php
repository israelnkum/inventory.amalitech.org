<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffDesignation extends Model
{
    public function designation(){
        return $this->belongsTo('App\Designation');
    }
}
