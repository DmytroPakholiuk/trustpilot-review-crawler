<?php

return [
    'trustpilot' => [
        'all_languages' => env('ALL_LANGUAGES'),
        'urls' => str_getcsv(file_get_contents(__DIR__ . "/review_populator/trustpilot.csv"))
    ]
];
