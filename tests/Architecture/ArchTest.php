<?php

declare(strict_types=1);

use Codelabmw\InfiniteScroll\Facades\InfiniteScroll;
use Codelabmw\InfiniteScroll\SupportedStacks;

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->strict()->ignoring([
    InfiniteScroll::class,
    SupportedStacks::class,
]);
arch()->preset()->laravel();
