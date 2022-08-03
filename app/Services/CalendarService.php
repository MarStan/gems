<?php

declare(strict_types=1);

namespace App\Services;

class CalendarService
{
    public function __construct(private CalendarApiClient $calendarClient)
    {
    }

    public function getCalendarData(string $userGemsEmployeeToken): \Generator
    {
        do {
            $response = $this->calendarClient->request($userGemsEmployeeToken);
            yield $response;
        } while ($response['total'] > $response['per_page'] * $response['current_page']);
    }

}