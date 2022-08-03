<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\Factory;

class PersonDataApiClient
{
    public function __construct(
        protected Factory $factory,
        protected array $config
    ) {
    }

    public function request(string $email): ?array
    {
        return $this->factory
            ->baseUrl($this->config['base_url'])
            ->timeout($this->config['timeout'] ?? 25)
            ->withHeaders($this->config['headers'] ?? [])
            ->retry(10, 10000)
            ->asJson()
            ->get($email)
            ->throw()
            ->json();
    }
}