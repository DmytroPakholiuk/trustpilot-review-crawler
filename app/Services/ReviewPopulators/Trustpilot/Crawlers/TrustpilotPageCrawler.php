<?php

namespace App\Services\ReviewPopulators\Trustpilot\Crawlers;

use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewCardIdentifier;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class TrustpilotPageCrawler
{
    public function __construct(
        protected ReviewCardIdentifier $reviewCardIdentifier,
    )
    {

    }

    public function getReviewBodiesByPage($url): \Generator
    {
        $pageNumber = 1;
        $pagesPerRun = 5;
        $urlTemplate = Config::get('review_populator.trustpilot.all_languages') ?
            "$url?languages=all&" : "$url?";
        $errors = [];

        // as soon as we reach a 404 page, an error will be recorded
        while(!$errors) {
            $reviewCards = [];
            Log::channel('file_and_consoleif')->info("Processing {$urlTemplate} , pages: " . $pageNumber . "-" . $pageNumber + $pagesPerRun - 1);
            $responses = $this->crawlPages($urlTemplate, $pageNumber, $pagesPerRun);
            $errors = $responses['errors'];

            foreach ($responses['bodies'] as $body) {
                $reviewCards = array_merge($reviewCards, $this->reviewCardIdentifier->getSubMatches($body));
            }
            $pageNumber += $pagesPerRun;

            yield $reviewCards;
        }
    }

    /**
     * This method concurrently checks the pages for content
     * @param $urlTemplate
     * @param int $firstPage
     * @param int $limit
     * @param int $concurrency
     * @return array[]
     */
    protected function crawlPages($urlTemplate, int $firstPage = 1, int $limit = 5, int $concurrency = 5): array
    {
        $client = new Client();
        $requests = [];
        for ($i = 0; $i < $limit; $i++) {
            $pageNumber = $firstPage + $i;
            $requests[] = new Request('GET', $urlTemplate . "page=$pageNumber");
        }

        $bodies = [];
        $errors = [];

        $pool = new Pool($client, $requests, [
            'concurrency' => $concurrency,
            'fulfilled' => function ($response, $index) use ($firstPage, $urlTemplate, &$bodies) {
                Log::channel('file_and_consoleif')->info("Processing {$urlTemplate}page=" . $firstPage + $index);
                $bodies[] = $response->getBody()->getContents();
            },
            'rejected' => function ($reason, $index) use ($firstPage, $urlTemplate, &$errors) {
                Log::channel('file_and_consoleif')->info("Page " . $firstPage + $index . " has no reviews");
                $errors[] = [
                    'url' => $urlTemplate . "page=" . $firstPage + $index,
                    'error' => $reason->getMessage(),
                ];
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return [
            'bodies' => $bodies,
            'errors' => $errors,
        ];
    }
}
