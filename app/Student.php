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
    public function student_issue_item(){
        return $this->hasMany('App\StudentIssueItem');
    }
    protected $fillable =
        ['location_id','program_id','session_id','first_name','last_name',
            'other_name','dob','gender','email','registration_number',
            'student_number', 'phone_number', 'staff', 'qr_code'
    ];
}
