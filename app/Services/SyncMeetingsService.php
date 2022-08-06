<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Employee;
use App\Repositories\MeetingsRepository;
use App\Services\DTO\Meeting;
use Illuminate\Support\Facades\Log;

class SyncMeetingsService
{
    public function __construct(
        private CalendarService $calendarService,
        private MeetingsRepository $meetings,
    ) {
    }

    public function sync(Employee $employee)
    {
        Log::info('Start meeting sync for ' . $employee->email);
        $meeting = $this->meetings->getLastMeetingByEmployeeToken($employee->token);
        $lastMeetingDateChange = is_object($meeting) ? new \DateTime($meeting->changed) : null;

        foreach ($this->calendarService->getMeetings($employee->token) as $meeting) {
            Log::info('Got meeting from Calendar API ', $meeting->toArray());

            if ($lastMeetingDateChange && $lastMeetingDateChange <= $meeting->changed) {
                Log::info('Meetings for ' . $employee->email . ' already synced');
                break;
            }

            $this->saveMeeting($meeting);
        }
        Log::info('End meeting sync for ' . $employee->email);
    }

    private function saveMeeting(Meeting $meeting): void
    {
        $this->meetings->createOrUpdateFromDTO($meeting);
    }
}
