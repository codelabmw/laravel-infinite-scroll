<?php

declare(strict_types=1);

namespace Codelabmw\InfiniteScroll\Database\Factories;

use Codelabmw\Tests\Fixtures\TestModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TestModel>
 */
final class TestModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = TestModel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
