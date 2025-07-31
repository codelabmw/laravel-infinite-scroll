<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll;

use Illuminate\Support\ServiceProvider;

final class InfiniteScrollServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('infinite-scroll', fn (): InfiniteScroll => new InfiniteScroll());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
