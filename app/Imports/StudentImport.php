<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'first_name' =>$row['first_name'],
            'last_name' =>$row['last_name'],
            'other_name' =>$row['other_name'],
            'dob' =>$row['dob'],
            'gender' =>$row['gender'],
            'email' =>$row['email'],
        ]);
    }
}
