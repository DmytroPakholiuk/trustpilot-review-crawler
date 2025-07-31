<?php

namespace App\Services\ReviewPopulators\Trustpilot\Parsers;

use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewerCountryIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewerImageIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewerReviewCountIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewerUsernameIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Parsers\BaseParser;
use Illuminate\Database\Eloquent\Collection;

class ReviewerParser extends BaseParser
{
    public function __construct(
        protected ReviewerImageIdentifier $reviewerImageIdentifier,
        protected ReviewerCountryIdentifier $reviewerCountryIdentifier,
        protected ReviewerReviewCountIdentifier $reviewerReviewCountIdentifier,
        protected ReviewerUsernameIdentifier $reviewerUsernameIdentifier,
    )
    {

    }

    public function getUsernames(array $cardCollection): array
    {
        $usernames = [];
        foreach ($cardCollection as $card) {
            $username = $this->reviewerUsernameIdentifier->getFirstSubMatch($card);
            $usernames[] = $username;
        }

        return $usernames;
    }

    public function getReviewersData(array $cardCollection, Collection $imageMap): array
    {
        $reviewers = [];
        foreach ($cardCollection as $card) {
            $username = $this->reviewerUsernameIdentifier->getFirstSubMatch($card);
            $country = $this->reviewerCountryIdentifier->getFirstSubMatch($card);
            $reviews_count = $this->reviewerReviewCountIdentifier->getFirstSubMatch($card);
            $image_id = $this->getImageId($card, $imageMap);

            $reviewers[] = compact('username', 'country', 'reviews_count', 'image_id');
        }

        return $reviewers;
    }

    protected function getImageId(string $card, Collection $imageMap)
    {
        $imageId = $this->reviewerImageIdentifier->checkIfMatches($card) ?
            $imageMap[$this->reviewerImageIdentifier->getFirstSubMatch($card)]?->id :
            null;

        return $imageId;
    }
}
