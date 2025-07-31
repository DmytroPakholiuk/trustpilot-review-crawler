<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

class ReviewTimeIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|<time.*?dateTime="(.+?)".+?data-service-review-date-time-ago="true".+?>|';
    }
}
