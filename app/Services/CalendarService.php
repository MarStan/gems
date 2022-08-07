<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Api\CalendarApiClient;
use App\Services\DTO\Meeting;

class CalendarService
{
    public function __construct(private CalendarApiClient $apiClient)
    {
    }

    public function getMeetings(string $employeeToken): \Generator
    {
        foreach ($this->getMeetingsPage($employeeToken) as $page) {
            foreach ($page['data'] ?? [] as $meeting) {

                yield Meeting::fromArray($meeting);
            }
        }
    }

    private function getMeetingsPage(string $employeeToken): \Generator
    {
        $page = 0;
        do {
            $response = $this->apiClient->request($employeeToken, ++$page);
            yield $response;
        } while ($response['total'] > $response['per_page'] * $response['current_page']);
    }


}
