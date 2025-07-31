<?php

namespace App\Repositories\Reviewer;

use App\Models\Reviewer;

class ReviewerRepository
{
    public function upsertReviewers(array $data): void
    {
        Reviewer::query()->upsert($data, ['username'], ['image_id', 'reviews_count', 'country']);
    }

    public function getReviewersByUsernames(array $usernames)
    {
        return Reviewer::query()->whereIn('username', $usernames)->get()->keyBy('username');
    }
}
