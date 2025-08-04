<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll\Contracts;

use Illuminate\Support\Collection;

interface Stack
{
    /**
     * The display name of the stack.
     */
    public function getLabel(): string;

    /**
     * The default installation path of components for this stack.
     */
    public function getDefaultInstallationPath(): string;

    /**
     * The paths of the stubs to copy.
     *
     * @return Collection<int, string>
     */
    public function getStubs(): Collection;

    /**
     * Whether this stack is the current one.
     */
    public function isCurrent(): bool;
}
