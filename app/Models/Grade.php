<?php

namespace App\Models;

use Eloquent;

class Grade extends Eloquent
{
    protected $fillable = ['name', 'class_type_id', 'scheme', 'mark_from', 'mark_to', 'remark'];
}
