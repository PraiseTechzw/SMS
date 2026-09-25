<?php

namespace App\Models;

use Eloquent;

class StudentSubjectCombination extends Eloquent
{
    protected $fillable = ['student_id', 'subject_id', 'level'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function student()
    {
        return $this->belongsTo(\App\User::class, 'student_id');
    }
}
