<?php

namespace App\Services\ReviewPopulators\Trustpilot\Crawlers;

use App\Services\ReviewPopulators\Trustpilot\Identifiers\ReviewCardIdentifier;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Config;

class TrustpilotPageCrawler
{
    public function __construct(
        protected ReviewCardIdentifier $reviewCardIdentifier,
    )
    {

    }

    public function getReviewBodiesByPage($url)
    {
        $pageNumber = 1;
        $pagesPerRun = 5;
        $urlTemplate = Config::get('review_populator.trustpilot.all_languages') ?
            "$url?languages=all&" : "$url?";
        $errors = [];

        while(!$errors) {
            $reviewCards = [];
            echo "Processing {$urlTemplate} , pages: " . $pageNumber . "-" . $pageNumber + $pagesPerRun - 1 . " \n";
            $responses = $this->crawlPages($urlTemplate, $pageNumber, $pagesPerRun);
            $errors = $responses['errors'];

            foreach ($responses['bodies'] as $body) {
                $reviewCards = array_merge($reviewCards, $this->reviewCardIdentifier->getSubMatches($body));
            }
            $pageNumber += $pagesPerRun;

            yield $reviewCards;
        }
    }

    protected function crawlPages($urlTemplate, $firstPage = 1, $limit = 5, $concurrency = 5): array
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
                echo "Processing {$urlTemplate}page=" . $firstPage + $index . "\n";
                $bodies[] = $response->getBody()->getContents();
            },
            'rejected' => function ($reason, $index) use ($firstPage, $urlTemplate, &$errors) {
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
