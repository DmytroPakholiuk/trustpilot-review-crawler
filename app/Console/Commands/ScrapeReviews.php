<?php

namespace App\Console\Commands;

use App\Services\ImageDownloader\ImageDownloader;
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
        echo "Starting the population process...\n";
        $populator = app()->get(ReviewPopulator::class);
        $populator->populateReviews();
        echo "Population process finished\n";

        $imageDownloader = app()->get(ImageDownloader::class);
        echo "Fixing missing files...\n";
        $imageDownloader->fixMissingFiles();
        echo "Missing files fixed\n";

        echo "Downloading new images...\n";
        $imageDownloader->downloadNewImages();
        echo "New images downloaded\n";
    }
}
