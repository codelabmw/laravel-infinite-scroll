<?php

declare(strict_types=1);

use Codelabmw\InfiniteScroll\Enums\PaginationType;
use Codelabmw\InfiniteScroll\Facades\InfiniteScroll;
use Codelabmw\Tests\Fixtures\TestModel;
use Inertia\DeferProp;

beforeEach(function (): void {
    // Arrange
    TestModel::factory()->count(20)->create();
});

test('returns cursor pagination data given query', function (): void {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query());

    // Assert
    expect($result)->toBeArray();
    expect(array_keys($result))->toEqual(['test', 'type', 'cursor', 'hasMore', 'perPage']);
    expect($result['test'])->toBeInstanceOf(DeferProp::class);
    expect($result['type']())->toBeInstanceOf(PaginationType::class);
    expect($result['cursor']())->toBeString();
    expect($result['hasMore']())->toBeTrue();
    expect($result['perPage']())->toBeInt();
});

test('returns cursor pagination data given cursor object', function (): void {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query()->cursorPaginate());

    // Assert
    expect($result)->toBeArray();
    expect(array_keys($result))->toEqual(['test', 'type', 'cursor', 'hasMore', 'perPage']);
    expect($result['test'])->toBeInstanceOf(DeferProp::class);
    expect($result['type']())->toBeInstanceOf(PaginationType::class);
    expect($result['cursor']())->toBeString();
    expect($result['hasMore']())->toBeTrue();
    expect($result['perPage']())->toBeInt();
});

test('determines if cursor pagination has more pages', function (): void {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query(), 20);

    // Assert
    expect($result['hasMore']())->toBeFalse();
});

test('returns pagination data given a pagination object', function (): void {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query()->paginate());

    // Assert
    expect($result)->toBeArray();
    expect(array_keys($result))->toEqual(['test', 'type', 'page', 'hasMore', 'perPage']);
    expect($result['test'])->toBeInstanceOf(DeferProp::class);
    expect($result['type']())->toBeInstanceOf(PaginationType::class);
    expect($result['page']())->toBeInt();
    expect($result['hasMore']())->toBeTrue();
    expect($result['perPage']())->toBeInt();
});

test('returns pagination data given a simple pagination object', function (): void {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query()->simplePaginate());

    // Assert
    expect($result)->toBeArray();
    expect(array_keys($result))->toEqual(['test', 'type', 'page', 'hasMore', 'perPage']);
    expect($result['test'])->toBeInstanceOf(DeferProp::class);
    expect($result['type']())->toBeInstanceOf(PaginationType::class);
    expect($result['page']())->toBeInt();
    expect($result['hasMore']())->toBeTrue();
    expect($result['perPage']())->toBeInt();
});

test('determines if pagination has more pages', function () {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query()->paginate(perPage: 20));

    // Assert
    expect($result['hasMore']())->toBeFalse();
});
