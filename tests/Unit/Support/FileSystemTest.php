<?php

declare(strict_types=1);

use Codelabmw\InfiniteScroll\Support\FileSystem;

beforeEach(function (): void {
    $this->tmpDir = sys_get_temp_dir().'/fs_test_'.uniqid();
    mkdir($this->tmpDir, 0777, true);
});

afterEach(function (): void {
    if (is_dir($this->tmpDir)) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->tmpDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }

        rmdir($this->tmpDir);
    }
});

test('exists returns true for existing file and directory', function (): void {
    // Arrange
    $file = $this->tmpDir.'/foo.txt';
    file_put_contents($file, 'bar');

    // Act
    $fileExists = FileSystem::exists($file);
    $dirExists = FileSystem::exists($this->tmpDir);

    // Assert
    expect($fileExists)->toBeTrue();
    expect($dirExists)->toBeTrue();
});

test('exists returns false for non-existing file', function (): void {
    // Arrange
    $file = $this->tmpDir.'/does_not_exist.txt';

    // Act
    $result = FileSystem::exists($file);

    // Assert
    expect($result)->toBeFalse();
});

test('delete removes a file', function (): void {
    // Arrange
    $file = $this->tmpDir.'/delete_me.txt';
    file_put_contents($file, 'delete');

    // Act
    $deleted = FileSystem::delete($file);

    // Assert
    expect($deleted)->toBeTrue();
    expect(file_exists($file))->toBeFalse();
});

test('ensureDirectoryExists creates missing directory', function (): void {
    // Arrange
    $newDir = $this->tmpDir.'/nested/dir';
    expect(is_dir($newDir))->toBeFalse();

    // Act
    FileSystem::ensureDirectoryExists($newDir);

    // Assert
    expect(is_dir($newDir))->toBeTrue();
});

test('ensureDirectoryExists returns true if directory already exists', function (): void {
    // Arrange
    $existingDir = $this->tmpDir;

    // Act
    $result = FileSystem::ensureDirectoryExists($existingDir);

    // Assert
    expect($result)->toBeTrue();
});

test('copy copies a file', function (): void {
    // Arrange
    $source = $this->tmpDir.'/source.txt';
    $dest = $this->tmpDir.'/dest.txt';
    file_put_contents($source, 'copy me');

    // Act
    $copied = FileSystem::copy($source, $dest);

    // Assert
    expect($copied)->toBeTrue();
    expect(file_exists($dest))->toBeTrue();
    expect(file_get_contents($dest))->toBe('copy me');
});

test('getContents returns the contents of a file', function (): void {
    // Arrange
    $file = $this->tmpDir.'/test.txt';
    file_put_contents($file, 'test');

    // Act
    $contents = FileSystem::getContents($file);

    // Assert
    expect($contents)->toBe('test');
});

test('stubs returns correct stubs path', function (): void {
    // Arrange
    // No setup needed

    // Act
    $base = FileSystem::stubs();
    $sub = FileSystem::stubs('foo');

    // Assert
    expect(is_dir($base))->toBeTrue();
    expect($sub)->toEndWith('/stubs/foo');
});

test('tests returns correct tests path', function (): void {
    // Arrange
    // No setup needed

    // Act
    $base = FileSystem::tests();
    $sub = FileSystem::tests('bar');

    // Assert
    expect(is_dir($base))->toBeTrue();
    expect($sub)->toEndWith('/tests/bar');
});
