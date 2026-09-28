<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded =[];
    
    public function institute(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function studentCourses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentCourse::class);
    }

    public function classHistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StudentClassHistory::class);
    }
}
