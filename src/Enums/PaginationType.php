<?php

namespace Codelabmw\InfiniteScroll\Enums;

enum PaginationType: string
{
    case CURSOR = 'cursor';
    case PAGED = 'paged';
}