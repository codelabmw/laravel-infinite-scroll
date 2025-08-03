<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll\Console\Commands;

use Codelabmw\InfiniteScroll\Contracts\Stack;
use Codelabmw\InfiniteScroll\SupportedStacks;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

final class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:infinite-scroll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Infinite Scrolling components for Inertia.';

    /**
     * List of registered supported stacks.
     *
     * @var Collection<string, class-string<Stack>>
     */
    private readonly Collection $supportedStacks;

    /**
     * Creates a new InstallCommand instance.
     */
    public function __construct(SupportedStacks $supportedStacksService)
    {
        parent::__construct();
        $this->supportedStacks = collect($supportedStacksService->get());
    }

    /**
     * Execute the console command.
     */
    public function handle(): ?int
    {
        /** @var string */
        $stack = select(
            label: 'Which stack are you using?',
            options: $this->supportedStacks->keys(), // @phpstan-ignore-line
            required: true,
        );

        /** @var Stack */
        $stack = App::make((string) $this->supportedStacks->get($stack));

        $installationPath = text(
            label: 'Where do you want to install components?',
            placeholder: $stack->getDefaultInstallationPath(),
            required: true,
        );

        $error = spin(fn (): ?string => $this->install($stack->getStubs()), 'Installing infinite scroll components for '.$stack->getLabel().' in '.$installationPath.'.');

        if ($error) {
            error('Error occurred while installing components. '.$error);

            return 1;
        }

        // @codeCoverageIgnoreStart
        info('Successfully installed components.');

        return null;
        // @codeCoverageIgnoreEnd
    }

    /**
     * Installs infinite scrolling frontend components.
     *
     * @param  Collection<int, string>  $stubs
     */
    private function install(Collection $stubs): ?string
    {
        if ($stubs->isEmpty()) {
            return 'Installation files were not found.';
        }

        $error = null;
        $stubs->each(function (string $file) use (&$error): void {
            if (! file_exists($file)) {
                $error = 'The file: '.$file.' does not exists.';

                return;
            }
        });

        return $error;
    }
}
