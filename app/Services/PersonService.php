<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Api\PersonDataApiClient;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PersonService
{
    public function __construct(private PersonDataApiClient $apiClient)
    {
    }

    public function getPerson($email): ?array
    {
        try {
            if (!$person = Cache::get($email)) {
                Log::info('No cache for person.', [$email]);
                $person = $this->apiClient->request($email);
                Cache::put($email, $person, now()->addDay());
                Log::info('Cache person request.', $person);
            }

            return $person;
        } catch (RequestException $exception) {
            Log::error('Something went wrong. ' . $exception->getMessage(), [
                'code' => $exception->getCode(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return null;
        }
    }
}