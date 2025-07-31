<?php

namespace App\Repositories\ReviewSubject;

use App\Models\ReviewSubject;

class ReviewSubjectRepository
{
    public function getOrCreateSubject(array $data)
    {
        $subject = ReviewSubject::query()->where(['url' => $data['url']])->first();
        if (!$subject) {
            $subject = ReviewSubject::query()->create($data);
        }

        return $subject;
    }
}
