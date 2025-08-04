<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll;

use Codelabmw\InfiniteScroll\Enums\PaginationType;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\CursorPaginator;
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
    public function make(string $key, Builder|CursorPaginator|Paginator $data, int $perPage = 15, array $columns = ['*']): array
    {
        if ($data instanceof Paginator) {
            return [
                $key => Inertia::defer(fn (): Paginator => $data)->deepMerge(),
                'type' => fn (): PaginationType => PaginationType::PAGED,
                'page' => fn () => $data->currentPage(),
                'has_more' => fn () => $data->hasMorePages(),
                'per_page' => fn (): int => $perPage,
            ];
        }

        if ($data instanceof Builder) {
            $data = $data->cursorPaginate(perPage: $perPage, columns: $columns);
        }

        return [
            $key => Inertia::defer(fn () => $data)->deepMerge(),
            'type' => fn (): PaginationType => PaginationType::CURSOR,
            'cursor' => fn () => $data->nextCursor()?->encode(),
            'has_more' => fn () => $data->hasMorePages(),
            'per_page' => fn (): int => $perPage,
        ];
    }
}
