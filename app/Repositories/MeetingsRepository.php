<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Meeting;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\DTO\Meeting as MeetingDTO;

class MeetingsRepository
{
    public function __construct(private PersonRepository $person)
    {
    }

    public function countMeetingsGroupByEmployees($userEmail): array
    {
        return DB::table('person_meeting', 'PM')
            ->join('people', 'people.id', 'PM.person_id')
            ->join('meetings', 'meetings.id', 'PM.meeting_id')
            ->join('employee_meeting', 'meetings.id', 'employee_meeting.meeting_id')
            ->join('employees', 'employees.id', 'employee_meeting.employee_id')
            ->where('people.email', $userEmail)
            ->where('meetings.start', '<', Carbon::now())
            ->groupBy('employees.email')
            ->select(DB::raw('count(employees.email) as amount, employees.email as `with`'))
            ->get()
            ->all();
    }

    public function countMeetings($userEmail): int
    {
        return DB::table('person_meeting', 'PM')
            ->join('people', 'people.id', 'PM.person_id')
            ->join('meetings', 'meetings.id', 'PM.meeting_id')
            ->where('people.email', $userEmail)
            ->where('meetings.start', '<', Carbon::now())
            ->count('meetings.id');
    }

    public function createOrUpdateFromDTO(MeetingDTO $meeting): void
    {
        DB::transaction(function () use ($meeting) {
            $meetingModel = Meeting::updateOrCreate([
                'id' => $meeting->id,
                'start' => $meeting->start->format('Y-m-d H:i:s'),
                'end' => $meeting->start->format('Y-m-d H:i:s'),
                'changed' => $meeting->changed->format('Y-m-d H:i:s'),
                'title' => $meeting->title,
            ]);

            foreach ($meeting->accepted as $email) {
                $this->person->createAndAttachToMeeting($email, $meetingModel);
            }
            foreach ($meeting->accepted as $email) {
                $this->person->createAndAttachToMeeting($email, $meetingModel);
            }
        });
    }

    public function getLastMeetingByEmployeeToken(string $token): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|\stdClass|null
    {
        return DB::table('meetings', 'M')
            ->join('employee_meeting', 'employee_meeting.meeting_id', 'M.id')
            ->join('employees', 'employee_meeting.employee_id', 'employees.id')
            ->where('employees.token', $token)
            ->first();
    }
}
