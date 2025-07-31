<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

class ReviewerUsernameIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|<span .*? data-consumer-name-typography="true">(.+?)</span>|';
    }
}
