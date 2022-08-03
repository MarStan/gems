<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected array $headers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->headers = [
            'Content-Type' => 'application/json',
        ];
    }
}
