<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffIssueItem extends Model
{
    public function staff(){
        return $this->belongsTo('App\Staff');
    }

    public function item(){
        return $this->belongsTo('App\Item');
    }
}
