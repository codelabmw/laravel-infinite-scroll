<?php

namespace Codelabmw\InfiniteScroll;

use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;
use Codelabmw\InfiniteScroll\Enums\PaginationType;

class InfiniteScroll
{
    /**
     * Sets up infinite scrolling props for a given query.
     * 
     * @param string $key
     * @param Builder|CursorPaginator|Paginator $paginator
     * @param int $perPage
     * @param array<int, string> $columns
     * 
     * @return array<string, mixed>
     */
    public function make(string $key, Builder|CursorPaginator|Paginator $paginator, int $perPage = 15, array $columns = ['*']): array
    {
        if ($paginator instanceof Paginator) {
            return [
                $key => Inertia::defer(fn() => $paginator->items())->deepMerge(),
                'type' => fn() => PaginationType::PAGED,
                'page' => fn() => $paginator->currentPage(),
                'hasMore' => fn() => $paginator->hasMorePages(),
                'perPage' => fn() => $perPage,
            ];
        }

        if ($paginator instanceof Builder) {
            $paginator = $paginator->cursorPaginate(perPage: $perPage, columns: $columns);
        }

        return [
            $key => Inertia::defer(fn() => $paginator->items())->deepMerge(),
            'type' => fn() => PaginationType::CURSOR,
            'cursor' => fn() => $paginator->nextCursor()?->encode(),
            'hasMore' => fn() => $paginator->hasMorePages(),
            'perPage' => fn() => $perPage,
        ];
    }
}