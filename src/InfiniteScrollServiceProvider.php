<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll;

use Codelabmw\InfiniteScroll\Console\Commands\InstallCommand;
use Illuminate\Support\ServiceProvider;

final class InfiniteScrollServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('infinite-scroll', fn (): InfiniteScroll => new InfiniteScroll());
        $this->app->singleton(SupportedStacks::class, fn (): SupportedStacks => new SupportedStacks());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
