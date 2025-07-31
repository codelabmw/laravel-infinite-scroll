<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @codeCoverageIgnore
 */
final class InfiniteScroll extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'infinite-scroll';
    }
}
