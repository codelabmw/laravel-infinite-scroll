<?php

namespace Codelabmw\InfiniteScroll\Facades;

use Illuminate\Support\Facades\Facade;

class InfiniteScroll extends Facade
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