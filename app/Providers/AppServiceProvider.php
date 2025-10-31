<?php

namespace App\Providers;

use App\Models\News;
use Illuminate\Container\Attributes\Tag;
use Illuminate\Support\ServiceProvider;
use App\Observers\NewsObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        News::observe(NewsObserver::class);

    }
}
