<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

class ReviewerImageIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|<img .*?data-consumer-avatar-image="true".*? src="(.+?)".*?>|';
    }
}
