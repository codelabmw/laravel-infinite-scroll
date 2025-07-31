<?php

declare(strict_types=1);

namespace Codelabmw\Tests;

use Codelabmw\InfiniteScroll\InfiniteScrollServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\Attributes\WithEnv;
use Orchestra\Testbench\TestCase as Orchestra;

#[WithEnv('DB_CONNECTION', 'testing')]
class TestCase extends Orchestra
{
    /**
     * Setup test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn(string $modelName): string => 'Codelabmw\\InfiniteScroll\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }
    
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        //
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations() 
    {
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );
    }

    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array<int, class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app)
    {
        return [
            InfiniteScrollServiceProvider::class,
        ];
    }
}
