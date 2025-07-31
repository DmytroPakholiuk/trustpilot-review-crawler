<?php

namespace App\Repositories\Reviewer;

use App\Models\Reviewer;

class ReviewerRepository
{
    public function createNewReviewers(array $data)
    {

    }

    public function getReviewersByUsernames(array $usernames)
    {
        return Reviewer::query()->whereIn('username', $usernames)->get()->keyBy('username');
    }
}
