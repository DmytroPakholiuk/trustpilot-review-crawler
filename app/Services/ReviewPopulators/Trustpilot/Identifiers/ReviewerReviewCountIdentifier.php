<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

class ReviewerReviewCountIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|data-consumer-reviews-count="([\d]+)"|';
    }
}
