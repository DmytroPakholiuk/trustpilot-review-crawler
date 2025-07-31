<?php

namespace App\Services\ReviewPopulators\Trustpilot;

use App\Repositories\Image\ImageRepository;
use App\Repositories\Reviewer\ReviewerRepository;
use App\Repositories\ReviewSubject\ReviewSubjectRepository;
use App\Repositories\Rewiew\ReviewRepository;
use App\Services\ReviewPopulators\ReviewPopulator;
use App\Services\ReviewPopulators\Trustpilot\Crawlers\TrustpilotPageCrawler;
use App\Services\ReviewPopulators\Trustpilot\Parsers\ImageParser;
use App\Services\ReviewPopulators\Trustpilot\Parsers\ReviewerParser;
use App\Services\ReviewPopulators\Trustpilot\Parsers\ReviewParser;
use Illuminate\Support\Facades\Config;

class TrustpilotReviewPopulator implements ReviewPopulator
{
    public function __construct(
        protected TrustpilotPageCrawler $crawler,
        protected ReviewSubjectRepository $subjectRepository,
        protected ImageRepository $imageRepository,
        protected ReviewerRepository $reviewerRepository,
        protected ReviewRepository $reviewRepository,
        protected ImageParser $imageParser,
        protected ReviewerParser $reviewerParser,
        protected ReviewParser $reviewParser,
    )
    {

    }

    public function populateReviews()
    {
        $urls = Config::get("review_populator.trustpilot.urls");
        if (!$urls) {
            throw new \Exception("No TrustPilot URLs specified in configs");
        }

        foreach ($urls as $url) {
            $subject = $this->subjectRepository->getOrCreateSubject([
                'url' => $url
            ]);

            foreach ($this->crawler->getReviewBodiesByPage($url) as $cardCollection) {
                $imageUrls = $this->imageParser->getImages($cardCollection);
                $this->imageRepository->createNewImagesFromUrls($imageUrls);
                $images = $this->imageRepository->getImagesByUrls($imageUrls);

                dd($images);
            }
        }
    }
}
