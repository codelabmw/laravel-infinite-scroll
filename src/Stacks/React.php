<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll\Stacks;

use Codelabmw\InfiniteScroll\Contracts\Stack;
use Codelabmw\InfiniteScroll\Support\FileSystem;
use Illuminate\Support\Collection;

final class React implements Stack
{
    /**
     * The display name of the stack.
     */
    public function getLabel(): string
    {
        return 'React';
    }

    /**
     * The default installation path of components for this stack.
     */
    public function getDefaultInstallationPath(): string
    {
        return resource_path('js/components');
    }

    /**
     * The paths of the stubs to copy.
     *
     * @return Collection<int, string>
     */
    public function getStubs(): Collection
    {
        return Collection::make([
            FileSystem::stubs('components/react/ts/infinite-scroll.tsx'),
        ]);
    }
}
