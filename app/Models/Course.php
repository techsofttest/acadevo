<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded =[];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_courses')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

}
