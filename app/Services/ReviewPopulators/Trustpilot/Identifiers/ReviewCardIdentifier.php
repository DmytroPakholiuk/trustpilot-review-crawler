<?php

namespace App\Services\ReviewPopulators\Trustpilot\Identifiers;

use App\Services\ReviewPopulators\Trustpilot\Identifiers\BaseIdentifier;

class ReviewCardIdentifier extends BaseIdentifier
{

    public function getRegex(): string
    {
        return '|<article class=".*?CDS_Card_card__.*?"(.*?)</article>|';
    }
}
