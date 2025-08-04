<?php

declare(strict_types=1);

use Codelabmw\InfiniteScroll\Stacks\React;
use Codelabmw\InfiniteScroll\SupportedStacks;

it('gets registered supported stacks', function (): void {
    // Arrange
    $mock = Mockery::mock(new SupportedStacks());
    $mock->shouldReceive('get')->andReturn([
        React::class,
    ]);

    $this->app->bind(SupportedStacks::class, fn () => $mock);

    // Act & Assert
    expect((new SupportedStacks)->get())->toEqual([
        React::class,
    ]);
});
