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

    public function staff_designation(){
        return $this->hasMany('App\StaffDesignation');
    }
    public function staff_issue_item(){
        return $this->hasMany('App\StaffIssueItem');
    }

    public function items(){
        return $this->hasMany('App\Item');
    }
}
