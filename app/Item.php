<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function item_type(){
        return $this->belongsTo('App\ItemType');
    }
    public function brand(){
        return $this->belongsTo('App\Brand');
    }
    public function ownership(){
        return $this->belongsTo('App\Ownership');
    }
    public function area(){
        return $this->belongsTo('App\Area');
    }
    public function status(){
        return $this->belongsTo('App\Status');
    }

    public function staff(){
        return $this->belongsTo('App\Staff');
    }

    public function staff_issued_items(){
        return $this->hasMany('App\StaffIssueItem');
    }

    public function student_issued_items(){
        return $this->hasMany('App\StudentIssueItem');
    }
}
