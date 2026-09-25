<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookRequest extends Model
{
    protected $fillable = ['book_id', 'user_id', 'start_date', 'end_date', 'returned', 'status'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
