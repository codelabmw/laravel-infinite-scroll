<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll;

use Codelabmw\InfiniteScroll\Enums\PaginationType;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

final class InfiniteScroll
{
    /**
     * Sets up infinite scrolling props for a given query.
     *
     * @param  array<int, string>  $columns
     * @return array<string, mixed>
     *
     * @phpstan-ignore-next-line missingType.generics
     */
    public function make(string $key, Builder|CursorPaginator|Paginator $paginator, int $perPage = 15, array $columns = ['*']): array
    {
        if ($paginator instanceof Paginator) {
            return [
                $key => Inertia::defer(fn () => $paginator->items())->deepMerge(),
                'type' => fn (): PaginationType => PaginationType::PAGED,
                'page' => fn () => $paginator->currentPage(),
                'hasMore' => fn () => $paginator->hasMorePages(),
                'perPage' => fn (): int => $perPage,
            ];
        }

        if ($paginator instanceof Builder) {
            $paginator = $paginator->cursorPaginate(perPage: $perPage, columns: $columns);
        }

        return [
            $key => Inertia::defer(fn () => $paginator->items())->deepMerge(),
            'type' => fn (): PaginationType => PaginationType::CURSOR,
            'cursor' => fn () => $paginator->nextCursor()?->encode(),
            'hasMore' => fn () => $paginator->hasMorePages(), // @phpstan-ignore-line method.notFound
            'perPage' => fn (): int => $perPage,
        ];
    }
}
