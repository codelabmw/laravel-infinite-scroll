<?php

declare(strict_types=1);

use Codelabmw\InfiniteScroll\Stacks\React;
use Illuminate\Support\Collection;

beforeEach(function (): void {
    $this->react = new React();
    $this->originalBasePath = base_path();

    $this->tmpBase = sys_get_temp_dir().'/react_stack_test_'.uniqid();
    mkdir($this->tmpBase, 0777, true);

    // Use Laravel's app()->setBasePath() to mock base_path
    app()->setBasePath($this->tmpBase);
});

afterEach(function (): void {
    // Restore original base path
    app()->setBasePath($this->originalBasePath);

    if (is_dir($this->tmpBase)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->tmpBase, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
        rmdir($this->tmpBase);
    }
});

test('getLabel returns React', function (): void {
    // Act
    $label = $this->react->getLabel();

    // Assert
    expect($label)->toBe('React');
});

test('getDefaultInstallationPath returns expected path', function (): void {
    // Act
    $path = $this->react->getDefaultInstallationPath();

    // Assert
    expect($path)->toBe(resource_path('js/components'));
});

test('getStubs returns correct stub path', function (): void {
    // Act
    $stubs = $this->react->getStubs();

    // Assert
    expect($stubs)->toBeInstanceOf(Collection::class);
    expect($stubs->first())->toContain('stubs/components/react/ts/infinite-scroll.tsx');
});

test('isCurrent returns true if @inertiajs/react is in dependencies', function (): void {
    // Arrange
    $packageJson = [
        'dependencies' => [
            '@inertiajs/react' => '^1.0.0',
        ],
    ];
    file_put_contents($this->tmpBase.'/package.json', json_encode($packageJson));

    // Act
    $result = $this->react->isCurrent();

    // Assert
    expect($result)->toBeTrue();
});

test('isCurrent returns true if @inertiajs/react is in devDependencies', function (): void {
    // Arrange
    $packageJson = [
        'devDependencies' => [
            '@inertiajs/react' => '^1.0.0',
        ],
    ];
    file_put_contents($this->tmpBase.'/package.json', json_encode($packageJson));

    // Act
    $result = $this->react->isCurrent();

    // Assert
    expect($result)->toBeTrue();
});

test('isCurrent returns false if @inertiajs/react is not present', function (): void {
    // Arrange
    $packageJson = [
        'dependencies' => [
            'vue' => '^3.0.0',
        ],
    ];
    file_put_contents($this->tmpBase.'/package.json', json_encode($packageJson));

    // Act
    $result = $this->react->isCurrent();

    // Assert
    expect($result)->toBeFalse();
});

test('isCurrent returns false if package.json is missing', function (): void {
    // Act
    $result = $this->react->isCurrent();

    // Assert
    expect($result)->toBeFalse();
});

test('isCurrent returns false if package.json is malformed', function (): void {
    // Arrange
    file_put_contents($this->tmpBase.'/package.json', '{not json');

    // Act
    $result = $this->react->isCurrent();

    // Assert
    expect($result)->toBeFalse();
});
