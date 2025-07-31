<?php

$trustpilotUrls = str_getcsv(file_get_contents(__DIR__ . "/review_populator/trustpilot.csv"));
$trustpilotUrls = array_map('trim', $trustpilotUrls);

return [
    'trustpilot' => [
        'quick_search' => env('QUICK_SEARCH', true),
        'all_languages' => env('ALL_LANGUAGES'),
        'urls' => $trustpilotUrls,
    ]
];
