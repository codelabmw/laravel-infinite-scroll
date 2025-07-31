<?php

declare(strict_types=1);
use Codelabmw\InfiniteScroll\Facades\InfiniteScroll;

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->strict()->ignoring([InfiniteScroll::class]);
arch()->preset()->laravel();
