<?php

use Codelabmw\InfiniteScroll\Facades\InfiniteScroll;
use Codelabmw\Tests\Fixtures\TestModel;
use Inertia\DeferProp;

beforeEach(function () {
    // Arrange
    TestModel::factory()->count(20)->create();
})->only();


test('returns cursor pagination data given query', function () {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query());

    // Assert
    expect($result)->toBeArray();
    expect(array_keys($result))->toEqual(['test', 'cursor', 'hasMore', 'perPage']);
    expect($result['test'])->toBeInstanceOf(DeferProp::class);
    expect($result['cursor']())->toBeString();
    expect($result['hasMore']())->toBeTrue();
    expect($result['perPage']())->toBeInt();
});

test('returns cursor pagination data given cursor object', function () {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query()->cursorPaginate());

    // Assert
    expect($result)->toBeArray();
    expect(array_keys($result))->toEqual(['test', 'cursor', 'hasMore', 'perPage']);
    expect($result['test'])->toBeInstanceOf(DeferProp::class);
    expect($result['cursor']())->toBeString();
    expect($result['hasMore']())->toBeTrue();
    expect($result['perPage']())->toBeInt();
});

test('returns pagination data given a pagination object', function () {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query()->paginate());

    // Assert
    expect($result)->toBeArray();
    expect(array_keys($result))->toEqual(['test', 'page', 'hasMore', 'perPage']);
    expect($result['test'])->toBeInstanceOf(DeferProp::class);
    expect($result['page']())->toBeInt();
    expect($result['hasMore']())->toBeTrue();
    expect($result['perPage']())->toBeInt();
});

test('returns pagination data given a simple pagination object', function () {
    // Act
    $result = InfiniteScroll::make('test', TestModel::query()->simplePaginate());

    // Assert
    expect($result)->toBeArray();
    expect(array_keys($result))->toEqual(['test', 'page', 'hasMore', 'perPage']);
    expect($result['test'])->toBeInstanceOf(DeferProp::class);
    expect($result['page']())->toBeInt();
    expect($result['hasMore']())->toBeTrue();
    expect($result['perPage']())->toBeInt();
});