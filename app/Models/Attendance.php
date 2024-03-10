<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    
    protected $table = 'attendance';
    protected $fillable = [
        'school_id',
        'student_id',
        'teacher_id',
        'class_id',
        'course_id',
        'status',
        'date',
    ];
}
