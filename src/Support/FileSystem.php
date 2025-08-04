<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll\Support;

final class FileSystem
{
    /**
     * Checks if a file or directory exists.
     */
    public static function exists(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * Deletes a file.
     */
    public static function delete(string $path): bool
    {
        return unlink($path);
    }

    /**
     * Ensures a directory exists.
     */
    public static function ensureDirectoryExists(string $path): bool
    {
        if (! self::exists($path)) {
            mkdir($path, 0777, true);
        }

        return true;
    }

    /**
     * Copies a file.
     */
    public static function copy(string $source, string $destination): bool
    {
        return copy($source, $destination);
    }

    /**
     * Returns the contents of a file.
     */
    public static function getContents(string $path): bool|string
    {
        return file_get_contents($path);
    }

    /**
     * Returns the path to the stubs directory.
     */
    public static function stubs(?string $path = null): string
    {
        if ($path === null) {
            return __DIR__.'/../../stubs';
        }

        return __DIR__.'/../../stubs/'.$path;
    }

    /**
     * Returns the path to the tests directory.
     */
    public static function tests(?string $path = null): string
    {
        if ($path === null) {
            return __DIR__.'/../../tests';
        }

        return __DIR__.'/../../tests/'.$path;
    }
}
