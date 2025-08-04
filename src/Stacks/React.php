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

    /**
     * Whether this stack is the current one.
     */
    public function isCurrent(): bool
    {
        $packageJsonPath = base_path('package.json');

        if (! FileSystem::exists($packageJsonPath)) {
            return false;
        }

        $json = json_decode((string) FileSystem::getContents($packageJsonPath), true);

        if (! is_array($json)) {
            return false;
        }

        /** @var array<string, string> */
        $deps = $json['dependencies'] ?? [];

        /** @var array<string, string> */
        $devDeps = $json['devDependencies'] ?? [];

        return isset($deps['@inertiajs/react']) || isset($devDeps['@inertiajs/react']);
    }
}
