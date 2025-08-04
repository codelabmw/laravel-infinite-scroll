<?php

declare(strict_types=1);

use Codelabmw\InfiniteScroll\Contracts\Stack;
use Codelabmw\InfiniteScroll\Stacks\React;
use Codelabmw\InfiniteScroll\Support\FileSystem;
use Codelabmw\InfiniteScroll\SupportedStacks;
use Illuminate\Support\Collection;

beforeEach(function (): void {
    // Arrange
    $mock = Mockery::mock(new SupportedStacks());
    $mock->shouldReceive('get')->andReturn([
        React::class,
    ]);

    $this->app->bind(SupportedStacks::class, fn () => $mock);

    $this->reactStack = new class implements Stack
    {
        public function getLabel(): string
        {
            return 'React';
        }

        public function isCurrent(): bool
        {
            return true;
        }

        public function getDefaultInstallationPath(): string
        {
            return 'resources/js/components';
        }

        public function getStubs(): Collection
        {
            return collect([]);
        }
    };

    $this->componentPath = FileSystem::tests('Fixtures/Storage/infinite-scroll.tsx');
});

it('requires input', function (): void {
    // Arrange
    $mock = Mockery::mock($this->reactStack);

    $this->app->bind(React::class, fn () => $mock);

    // Act & Assert
    $this->artisan('install:infinite-scroll')
        ->expectsQuestion('Which stack are you using?', 'React')
        ->expectsQuestion('Where do you want to install components?', 'resources/js/components');
});

it('aborts if stack has no stub files', function (): void {
    // Arrange
    $mock = Mockery::mock($this->reactStack);

    $this->app->bind(React::class, fn () => $mock);

    // Act & Assert
    $this->artisan('install:infinite-scroll')
        ->expectsQuestion('Which stack are you using?', 'React')
        ->expectsQuestion('Where do you want to install components?', 'resources/js/components')
        ->assertExitCode(1);
});

it('aborts if stack has stub files that does not exists', function (): void {
    // Arrange
    $mock = Mockery::mock($this->reactStack);
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

it('does not overwrite existing files', function (): void {
    // Arrange
    $mock = Mockery::mock($this->reactStack);
    $stubFile = FileSystem::stubs('components/react/ts/infinite-scroll.tsx');
    $destinationDir = FileSystem::tests('Fixtures/Storage');
    $destinationFile = $destinationDir.'/infinite-scroll.tsx';

    FileSystem::ensureDirectoryExists($destinationDir);
    file_put_contents($destinationFile, 'original content');

    $mock->shouldReceive('getStubs')->andReturn(collect([$stubFile]));
    $this->app->bind(React::class, fn () => $mock);

    // Act & Assert
    $this->artisan('install:infinite-scroll')
        ->expectsQuestion('Which stack are you using?', 'React')
        ->expectsQuestion('Where do you want to install components?', $destinationDir)
        ->assertExitCode(0);

    expect(file_get_contents($destinationFile))->toBe('original content');

    // Cleanup
    FileSystem::delete($destinationFile);
});

it('installs proper files', function (): void {
    // Arrange
    $mock = Mockery::mock($this->reactStack);
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
