<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $fillable = [
        'title',
        'rating',
        'content',
        'reviewer_id',
        'review_subject_id',
        'review_date',
        'experience_date',
    ];

    public function rewiewer()
    {
        return $this->belongsTo(Reviewer::class);
    }

    public function review_subject()
    {
        return $this->belongsTo(ReviewSubject::class);
    }
}
