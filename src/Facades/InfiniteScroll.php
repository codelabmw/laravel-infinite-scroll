<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll\Facades;

use Illuminate\Support\Facades\Facade;

final class InfiniteScroll extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'infinite-scroll';
    }
}
