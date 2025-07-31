<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

use App\Services\ReviewPopulators\Trustpilot\Identifiers\BaseIdentifier;

class ReviewDateOfExperienceIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|<p .*? data-service-review-date-of-experience-typography="true">.+?<span.+?>(.+?)</span></p>|';
    }
}
