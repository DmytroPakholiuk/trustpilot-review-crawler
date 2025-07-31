<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

class ReviewTitleIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|<h2 .*? data-service-review-title-typography="true">(.+?)</h2>|';
    }
}
