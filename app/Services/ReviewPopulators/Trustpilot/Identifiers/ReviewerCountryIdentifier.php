<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

class ReviewerCountryIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|<span .*? data-consumer-country-typography="true">(.+?)</span>|';
    }
}
