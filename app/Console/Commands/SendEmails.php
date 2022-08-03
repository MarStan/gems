<?php

namespace App\Console\Commands;

use App\Models\CalendarUser;
use App\Services\EmailService;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    protected $signature = 'mail:send';

    protected $description = 'Send morning sale update email';

    public function __construct(private  EmailService $emailService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        CalendarUser::latest('id')
            ->where('is_usergems_employee', true)
            ->chunk(100, function($users) {
                foreach ($users as $user) {
                    $this->emailService->sendEmails($user);
                }
            });
    }
}
