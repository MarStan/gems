<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Client\Factory;

class CalendarApiClient
{
    public function __construct(
        protected Factory $factory,
        protected array $config
    ) {
    }

    public function request(string $token, int $page = 1): ?array
    {
        $headers = [
            'Authorization' => 'Bearer ' . $token,
        ];
        return $this->factory
            ->baseUrl($this->config['base_url'])
            ->timeout($this->config['timeout'] ?? 20)
            ->withHeaders($headers ?? $this->config['headers'])
            ->retry(10, 10000)
            ->asJson()
            ->get('/', ['page' => $page])
            ->throw()
            ->json();
    }
}