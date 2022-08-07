<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\MorningSaleUpdate;
use App\Models\Employee;
use App\Repositories\MeetingsRepository;
use App\Services\DTO\Meeting as MeetingDTO;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use NumberFormatter;

class EmailService
{
    public function __construct(
        private PersonService $personService,
        private CalendarService $calendarService,
        private MeetingsRepository $meetings,
        private EmployeeService $employeeService,
    ) {
    }

    public function sendEmails(Employee $employee): void
    {
        $today = Carbon::now()->setTime(0, 0);
        //###################################################################################
        // FOR testing purposes, because there is no emails for today
        $today = Carbon::createFromFormat('Y-m-d H:i:s', '2022-07-01 00:00:00');
        //###################################################################################
        foreach ($this->calendarService->getMeetings($employee->token) as $meeting) {
            $start = clone $meeting->start;
            if ($start->setTime(0, 0) > $today) {
                continue;
            }

            if ($start < $today) {
                break;
            }
            $meetingData = $this->getMeetingDataForEmail($meeting);
            Mail::to($employee->email)->send(new MorningSaleUpdate($meetingData));
        }
    }

    public function getMeetingDataForEmail(MeetingDTO $meeting): array
    {
        $response = $this->getEmailGeneralData($meeting);
        $response['participants'] = $this->getParticipantsData($meeting);
        $response['company_name'] = reset($response['participants'])['company']['name'] ?? null;
        $response['company_employees'] = reset($response['participants'])['company']['employees'] ?? null;

        return $response;
    }

    private function getParticipantsData(MeetingDTO $meeting): array
    {
        $participants = [];

        foreach ($meeting->accepted as $email) {
            if ($personData = $this->getPersonData($email)) {
                $participants[$email] = $personData;
                $participants[$email]['accepted'] = true;
            }
        }
        foreach ($meeting->rejected as $email) {
            if ($personData = $this->getPersonData($email)) {
                $participants[$email] = $personData;
                $participants[$email]['accepted'] = false;
            }
        }

        return $participants;
    }

    private function getPersonData(string $email): ?array
    {
        if (!$this->employeeService->isEmployeeEmail($email)) {
            if ($personData = $this->personService->getPerson($email)) {
                $meetingsAmount = $this->meetings->countMeetingsGroupByEmployees($email);
                $metWith = [];
                foreach ($meetingsAmount as $meeting) {
                    $name = $this->employeeService->getNameFromEmail($meeting->with);
                    $metWith[] = "{$name} ({$meeting->amount}x)";
                }
                $personData['met_with'] = implode(' & ', $metWith);
                $amount = $this->meetings->countMeetings($email);
                $personData['meeting_number'] = (new NumberFormatter('en_US', NumberFormatter::ORDINAL))->format($amount);
                return $personData;
            }
        }

        return null;
    }

    private function getEmailGeneralData(MeetingDTO $meeting): array
    {
        return [
            'start' => $meeting->start->format('h:i A'),
            'end' => $meeting->end->format('h:i A'),
            'duration' => ($meeting->end->getTimestamp() - $meeting->start->getTimestamp()) / 60,
            'title' => $meeting->title,
            'joining_from_usergems' => $this->employeeService->getMeetingsEmployeesNamesAndMeetingAccept($meeting),
        ];
    }
}
