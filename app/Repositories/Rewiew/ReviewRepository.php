<?php

namespace App\Repositories\Rewiew;

use App\Models\Review;

class ReviewRepository
{
    /**
     * it checks whether we already have the reviews by comparing the hashes of results
     * comparing hashes is easier than cross-checking multiple fields manually
     *
     * @param $data
     * @return int - amount of rows that should be inserted
     */
    public function insertNewReviews($data): int
    {
        $finalReviews = [];

        // retrieving hashes of older reviews with the same content. We collect them as array keys
        // to simplify the search later
        $existingReviews = Review::query()
            ->whereIn('content', array_column($data, 'content'))
            ->whereIn('reviewer_id', array_column($data, 'reviewer_id'))
            ->get();
        $existingHashes = $existingReviews->mapWithKeys(function ($review) {
            return [md5($review->reviewer_id . '|' . $review->content) => true];
        })->toArray();

        foreach ($data as $review) {
            $hash = md5($review['reviewer_id'] . '|' . $review['content']);
            if (!isset($existingHashes[$hash])) {
                $finalReviews[] = $review + [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
            }
        }
        Review::query()->insert($finalReviews);

        return count($finalReviews);
    }
}
