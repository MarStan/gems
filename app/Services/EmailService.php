<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\MorningSaleUpdate;
use App\Models\CalendarUser;
use App\Repositories\MeetingsRepository;
use App\Services\Api\PersonDataApiClient;
use DateTime;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    public function __construct(
        private PersonDataApiClient $personClient,
        private CalendarService $calendarService,
        private MeetingsRepository $meetings,
    ) {
    }

    public function sendEmails(CalendarUser $user): void
    {
        //TODO: where start is today
        foreach ($this->calendarService->getCalendarData($user->token) as $page) {
            foreach ($page['data'] as $meeting) {
                $meetingData = $this->getMeetingData($meeting);
                Mail::to($user->email)->send(new MorningSaleUpdate($meetingData));
            }
        }
    }

    public function getMeetingData(array $meeting): array
    {
        $data = [];

        foreach ($this->filterUsergemsEmails($meeting['accepted']) as $email) {
            if ($personData = $this->getPersonData($email)) {
                $data['accepted'][$email] = $personData;
            }
        }
        foreach ($this->filterUsergemsEmails($meeting['rejected']) as $email) {
            if ($personData = $this->getPersonData($email)) {
                $data['rejected'][$email] = $personData;
            }
        }

        $data['metadata'] = $this->getEmailMetadata($meeting);
        $data['metadata']['company'] = reset($data['accepted'])['company']['name'] ?? null;
        $data['metadata']['employees'] = reset($data['accepted'])['company']['employees'] ?? null;

        return $data;
    }

    private function getPersonData(string $email): ?array
    {
        $meetingsAmount = $this->meetings->countMeetingsWithUsergems($email);

        try {
            $data = $this->personClient->request($email);
            //TODO: finish that
            $data['met_with'] = 'Christian (1x) & Blaise (4x)';
            $data['meeting_number'] = '12th';

            return $data;
        } catch (RequestException $exception) {
            Log::error('Something went wrong. ' . $exception->getMessage(), [
                'code' => $exception->getCode(),
                'trace' => $exception->getTraceAsString(),
            ]);

            return null;
        }
    }

    private function getEmailMetadata(array $meeting): array
    {
        $start = new DateTime($meeting['start']);
        $end = new DateTime($meeting['end']);

        return [
            'changed' => $meeting['changed'],
            'start' => $start->format('h:i A'),
            'end' => $end->format('h:i A'),
            'duration' => ($end->getTimestamp() - $start->getTimestamp()) / 60,
            'title' => $meeting['title'],
            //TODO: missing info
            'joining_from_usergems' => [
                [
                    'first_name' => 'Joss',
                    'accepted' => true,
                ],
            ],
        ];
    }

    private function filterUsergemsEmails(array $emails): array
    {
        $return = [];

        foreach ($emails as $email) {
            if (!str_ends_with($email, '@usergems.com')) {
                $return[] = $email;
            }
        }

        return $return;
    }
}
