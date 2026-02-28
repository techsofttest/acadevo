<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded =[];
    
    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }


    public function studentCourses()
    {
        return $this->hasMany(StudentCourse::class);
    }


}
