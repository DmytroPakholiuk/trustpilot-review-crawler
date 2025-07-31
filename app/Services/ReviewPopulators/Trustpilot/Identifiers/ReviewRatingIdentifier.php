<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

class ReviewRatingIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|data-service-review-rating="([\d.]+)"|';
    }
}
