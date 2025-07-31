<?php

namespace App\Services\ReviewPopulators\Trustpilot\Parsers;

use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewerImageIdentifier;

class ImageParser extends BaseParser
{
    public function __construct(
        protected ReviewerImageIdentifier $reviewerImageIdentifier
    )
    {

    }

    public function getImages(array $cardCollection): array
    {
        $images = [];
        foreach ($cardCollection as $card) {
            if ($this->reviewerImageIdentifier->checkIfMatches($card)) {
                $imageUrl = $this->reviewerImageIdentifier->getFirstSubMatch($card);
                $images[] = $imageUrl;
            }
        }

        return $images;
    }
}
