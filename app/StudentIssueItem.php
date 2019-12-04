<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentIssueItem extends Model
{
    public function student(){
        return $this->belongsTo('App\Student');
    }
    public function item(){
        return $this->belongsTo('App\Item');
    }
}
