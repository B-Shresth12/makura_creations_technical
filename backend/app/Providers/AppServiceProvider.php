<?php

namespace App\Providers;

use App\Models\Url;
use App\Observers\UrlObserver;
use App\Repositories\Url\UrlRepository;
use App\Repositories\Url\UrlRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UrlRepositoryInterface::class, UrlRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Url::observe(UrlObserver::class);

    }
}
