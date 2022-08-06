<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Meeting;
use App\Models\Person;
use Illuminate\Support\Facades\DB;

class MeetingsRepository
{
    public function countMeetingsWithUsergems($userEmail): array
    {
        /**
         *
        select count(CUM4.id) as amount, cu2.email as 'email'
        from `calendar_users` as `cu1`
        inner join `calendar_user_meeting` CUM on `cu1`.`id` = `CUM`.`calendar_user_id`
        inner join `meetings` m1 on `m1`.`id` = `CUM`.`meeting_id`

        inner join `calendar_user_meeting` CUM2 on `CUM2`.`meeting_id` = `m1`.`id`
        inner join `calendar_users` cu2 on `cu2`.`id` = `CUM2`.`calendar_user_id`

        inner join `calendar_user_meeting` CUM3 on `CUM3`.`calendar_user_id` = `cu2`.`id`
        inner join `meetings` m2 on `m2`.`id` = `CUM3`.`meeting_id`
        inner join `calendar_user_meeting` CUM4 on `CUM4`.`meeting_id` = `m2`.`id` and CUM4.calendar_user_id = cu1.id

        where `cu1`.`email` = 'demi@algolia.com'
        and m1.start < NOW()
        and m2.start < NOW()
        and CUM.meeting_id = 322
        group by cu2.email
         */
        return DB::table('calendar_user_meeting as CUM')
            ->join('calendar_users ', 'calendar_users.id', 'CUM.calendar_user_id')
            ->join('meetings', 'meetings.id', 'CUM.meeting_id')
            ->where('calendar_users.email', $userEmail)
            ->select(DB::raw('count(meetings.id) as amount, calendar_users.email'))
            ->groupBy('calendar_users.email')
            ->get()
            ->all();
    }

    public function createOrUpdate(array $meeting)
    {
        //TODO: check if user is from usergems
        //TODO: update by chunk
        //TODO: add transaction
        $meetingModel = Meeting::create($meeting['meeting']);
        foreach ($meeting['users']['accepted'] ?? [] as $email) {
            $person = Person::updateOrCreate(['email' => $email]);
            $meetingModel->persons()->attach($person);
        }
        foreach ($meeting['users']['rejected'] ?? [] as $email) {
            $person = Person::updateOrCreate(['email' => $email]);
            $meetingModel->persons()->attach($person);
        }
    }
}
