<?php

namespace App\Providers;

use App\Models\Url;
use App\Observers\UrlObserver;
use App\Repositories\Url\UrlRepository;
use App\Repositories\Url\UrlRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UrlRepositoryInterface::class, UrlRepository::class);
        // $this->app->bind(UrlRepositoryInterface::class, UrlRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Url::observe(UrlObserver::class);
        // Passport::loadKeysFrom(__DIR__.'/../secrets/oauth');
        // $this->registerPolicies();
        Passport::enablePasswordGrant();

    }
}
