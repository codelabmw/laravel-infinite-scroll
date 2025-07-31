<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll\Enums;

enum PaginationType: string
{
    case CURSOR = 'cursor';
    case PAGED = 'paged';
}
