<?php

namespace App\Providers;

use App\Services\ReviewPopulators\ReviewPopulator;
use App\Services\ReviewPopulators\Trustpilot\TrustpilotReviewPopulator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReviewPopulator::class, TrustpilotReviewPopulator::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
