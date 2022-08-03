<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\CalendarUser;
use App\Repositories\MeetingsRepository;

class SyncService
{
    public function __construct(
        private CalendarApiClient $calendarClient,
        private CalendarService $calendarService,
        private MeetingsRepository $meetings,
    ) {
    }

    public function sync(CalendarUser $user)
    {
        foreach ($this->calendarService->getCalendarData($user->token) as $page) {
            foreach ($page['data'] as $meeting) {
                $meeting = $this->prepareMeetingData($meeting);
                $this->saveMeeting($meeting);

            }
        }
    }

    private function prepareMeetingData(array $meeting): array
    {
        return [
            'meeting' => [
                'start' => $meeting['start'],
                'end' => $meeting['end'],
                'title' => $meeting['title'],
            ],
            'users' => [
                'accepted' => $meeting['accepted'],
                'rejected' => $meeting['rejected'],
            ]
        ];
    }

    private function saveMeeting(array $meeting): void
    {
        $this->meetings->createOrUpdate($meeting);
    }
}