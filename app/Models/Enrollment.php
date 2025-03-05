<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'academic_year',
        'semester'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'enrollment_subject')
            ->withPivot('grade')
            ->withTimestamps();
    }
} 
