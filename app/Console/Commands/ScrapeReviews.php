<?php

namespace App\Console\Commands;

use App\Services\ReviewPopulators\ReviewPopulator;
use Illuminate\Console\Command;

class ScrapeReviews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:scrape-reviews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes reviews from web and stores them in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $populator = app()->get(ReviewPopulator::class);
        $populator->populateReviews();
    }
}
