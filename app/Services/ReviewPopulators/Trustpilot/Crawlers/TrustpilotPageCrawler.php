<?php

namespace App\Services\ReviewPopulators\Trustpilot\Crawlers;

use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewCardIdentifier;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Config;

class TrustpilotPageCrawler
{
    public function getReviewBodiesByPage($url)
    {
        $client = new Client();
        $reviewCardIdentifier = new ReviewCardIdentifier();
        $pageNumber = 1;
        $urlTemplate = Config::get('review_populator.trustpilot.all_languages') ?
            "$url?languages=all&" : "$url?";

        while (true) {
            try {
                $response = $client->get("{$urlTemplate}page=$pageNumber");
                $bodyText = $response->getBody()->getContents();
                $reviewCards = $reviewCardIdentifier->getSubMatches($bodyText);
                $pageNumber++;

                yield $reviewCards;
            } catch (GuzzleException $exception) {
                break;
            }
        }
    }
}
