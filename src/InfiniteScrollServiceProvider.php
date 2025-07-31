<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll;

use Illuminate\Support\ServiceProvider;
use Override;

final class InfiniteScrollServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    #[Override]
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
