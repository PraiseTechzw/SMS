<?php

namespace App\Models;

use Eloquent;

class Notice extends Eloquent
{
    protected $fillable = ['title', 'body', 'audience', 'published_at', 'expires_at', 'is_published'];

    protected $casts = ['published_at' => 'date', 'expires_at' => 'date', 'is_published' => 'boolean'];

    public function scopeVisible($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) { $q->whereNull('published_at')->orWhereDate('published_at', '<=', now()); })
            ->where(function ($q) { $q->whereNull('expires_at')->orWhereDate('expires_at', '>=', now()); });
    }
}
