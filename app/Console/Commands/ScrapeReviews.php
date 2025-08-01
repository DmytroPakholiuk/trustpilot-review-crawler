<?php

namespace App\Console\Commands;

use App\Services\ImageDownloader\ImageDownloader;
use App\Services\ReviewPopulators\ReviewPopulator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        Log::channel('file_and_consoleif')->info("Starting the population process...\n");
        $populator = app()->get(ReviewPopulator::class);
        $populator->populateReviews();
        Log::channel('file_and_consoleif')->info("Population process finished\n");

        $imageDownloader = app()->get(ImageDownloader::class);
        Log::channel('file_and_consoleif')->info("Fixing missing files...\n");
        $imageDownloader->fixMissingFiles();
        Log::channel('file_and_consoleif')->info("Missing files fixed\n");

        Log::channel('file_and_consoleif')->info("Downloading new images...\n");
        $imageDownloader->downloadNewImages();
        Log::channel('file_and_consoleif')->info("New images downloaded\n");
    }
}
