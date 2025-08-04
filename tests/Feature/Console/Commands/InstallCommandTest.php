<?php

declare(strict_types=1);

use Codelabmw\InfiniteScroll\Stacks\React;
use Codelabmw\InfiniteScroll\Support\FileSystem;
use Codelabmw\InfiniteScroll\SupportedStacks;

beforeEach(function (): void {
    // Arrange
    $mock = Mockery::mock(new SupportedStacks());
    $mock->shouldReceive('get')->andReturn([
        'React' => React::class,
    ]);

    $this->app->bind(SupportedStacks::class, fn () => $mock);

    $this->componentPath = FileSystem::tests('Fixtures/Storage/infinite-scroll.tsx');
});

it('requires input', function (): void {
    // Act & Assert
    $this->artisan('install:infinite-scroll')
        ->expectsQuestion('Which stack are you using?', 'React')
        ->expectsQuestion('Where do you want to install components?', 'resources/js/components');
});

it('aborts if stack has no stub files', function (): void {
    // Arrange
    $mock = Mockery::mock(new React());
    $mock->shouldReceive('getStubs')->andReturn(collect([]));

    $this->app->bind(React::class, fn () => $mock);

    // Act & Assert
    $this->artisan('install:infinite-scroll')
        ->expectsQuestion('Which stack are you using?', 'React')
        ->expectsQuestion('Where do you want to install components?', 'resources/js/components')
        ->assertExitCode(1);
});

it('aborts if stack has stub files that does not exists', function (): void {
    // Arrange
    $mock = Mockery::mock(new React());
    $mock->shouldReceive('getStubs')->andReturn(collect([
        FileSystem::stubs('none-existent.file'),
    ]));

    $this->app->bind(React::class, fn () => $mock);

    // Act & Assert
    $this->artisan('install:infinite-scroll')
        ->expectsQuestion('Which stack are you using?', 'React')
        ->expectsQuestion('Where do you want to install components?', 'resources/js/components')
        ->assertExitCode(1);
});

it('installs proper files', function (): void {
    // Arrange
    $mock = Mockery::mock(new React());
    $mock->shouldReceive('getStubs')->andReturn(collect([
        FileSystem::stubs('components/react/ts/infinite-scroll.tsx'),
    ]));

    $this->app->bind(React::class, fn () => $mock);

    // Act & Assert
    $this->artisan('install:infinite-scroll')
        ->expectsQuestion('Which stack are you using?', 'React')
        ->expectsQuestion('Where do you want to install components?', FileSystem::tests('Fixtures/Storage'))
        ->assertExitCode(0);

    expect(FileSystem::exists($this->componentPath))->toBeTrue();
})->after(function (): void {
    if (FileSystem::exists($this->componentPath)) {
        FileSystem::delete($this->componentPath);
    }
});
