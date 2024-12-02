<?php

namespace App\Providers;

use App\Models\Url;
use App\Observers\UrlObserver;
use App\Repositories\Url\UrlRepository;
use App\Repositories\Url\UrlRepositoryInterface;
use App\Repositories\Visitor\VisitorRepository;
use App\Repositories\Visitor\VisitorRepositoryInterface;
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
        $this->app->bind(VisitorRepositoryInterface::class, VisitorRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Url::observe(UrlObserver::class);
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    }
}
