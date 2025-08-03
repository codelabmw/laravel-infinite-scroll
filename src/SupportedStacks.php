<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll;

use Codelabmw\InfiniteScroll\Contracts\Stack;
use Codelabmw\InfiniteScroll\Stacks\React;

class SupportedStacks
{
    /**
     * @return array<string, class-string<Stack>>
     */
    public function get(): array
    {
        return [
            'React' => React::class,
        ];
    }
}
