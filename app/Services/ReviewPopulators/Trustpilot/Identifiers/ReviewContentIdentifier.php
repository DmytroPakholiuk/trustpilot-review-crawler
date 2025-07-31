<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

class ReviewContentIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|<p .*? data-service-review-text-typography="true">(.+?)</p>|';
    }
}
