<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'email',
        'phone',
        'address',
        'course',
        'year_level'
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
