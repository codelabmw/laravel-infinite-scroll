<?php

namespace Codelabmw\Tests\Fixtures;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    /** @uses HasFactory<\Codelabmw\InfiniteScroll\Database\Factories\TestModelFactory> */
    use HasFactory;

    /** @var array<string, mixed> */
    protected $guarded = [];
}