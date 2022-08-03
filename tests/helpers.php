<?php

use Illuminate\Support\Arr;

function create(string $class, array $attrs = [], array | string $states = [], ?int $times = null)
{
    $factory = $class::factory()->count($times);

    foreach (Arr::wrap($states) as $state) {
        $factory = $factory->{$state}();
    }

    return $factory->create($attrs);
}

function user()
{
    return auth()->user();
}
