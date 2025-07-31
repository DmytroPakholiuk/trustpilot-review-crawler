<?php

namespace App\Services\ReviewPopulators\Trustpilot\Parsers;

use App\Models\ReviewSubject;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewContentIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewDateOfExperienceIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewerUsernameIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewRatingIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewTimeIdentifier;
use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewTitleIdentifier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ReviewParser extends BaseParser
{
    public function __construct(
        protected ReviewContentIdentifier $reviewContentIdentifier,
        protected ReviewTimeIdentifier $reviewTimeIdentifier,
        protected ReviewTitleIdentifier $reviewTitleIdentifier,
        protected ReviewRatingIdentifier $reviewRatingIdentifier,
        protected ReviewDateOfExperienceIdentifier $reviewDateOfExperienceIdentifier,
        protected ReviewerUsernameIdentifier $reviewerUsernameIdentifier,
    )
    {

    }

    public function getReviewsData(array $cardCollection, Collection $usernameMap, ReviewSubject $subject): array
    {
        $reviews = [];
        foreach ($cardCollection as $card) {
            $title = $this->reviewTitleIdentifier->getFirstSubMatch($card);
            $rating = $this->reviewRatingIdentifier->getFirstSubMatch($card);
            $content = $this->reviewContentIdentifier->getFirstSubMatch($card);
            $review_date = $this->getReviewDate($card);
            $experience_date = $this->getExperienceDate($card);
            $reviewer_id = $this->getReviewerId($card, $usernameMap);
            $review_subject_id = $subject->id;

            $reviews[] = compact(
                'title',
                'rating',
                'content',
                'review_date',
                'experience_date',
                'reviewer_id',
                'review_subject_id',
            );
        }

        return $reviews;
    }

    protected function getReviewDate(string $card): string
    {
        $isoDate = $this->reviewTimeIdentifier->getFirstSubMatch($card);

        return Carbon::parse($isoDate)->toDateString();
    }

    protected function getExperienceDate(string $card): string
    {
        $stringDate = $this->reviewDateOfExperienceIdentifier->getFirstSubMatch($card);

        return Carbon::parse($stringDate)->toDateString();
    }

    protected function getReviewerId(string $card, Collection $usernameMap): int
    {
        $reviewerId = $usernameMap[$this->reviewerUsernameIdentifier->getFirstSubMatch($card)]?->id;

        return $reviewerId;
    }
}
