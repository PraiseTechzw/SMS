<?php

namespace App\Models;

use Eloquent;

class Attendance extends Eloquent
{
    protected $fillable = ['student_id', 'attendance_date', 'status', 'remarks'];

    protected $casts = ['attendance_date' => 'date'];

    public function student()
    {
        return $this->belongsTo(\App\User::class, 'student_id');
    }
}
