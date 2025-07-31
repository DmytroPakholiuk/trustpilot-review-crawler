<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewSubject extends Model
{
    protected $fillable = [
        'url'
    ];

    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }
}
