<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\EmailService;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    protected $signature = 'emails:send';

    protected $description = 'Send morning sale update email';

    public function __construct(private  EmailService $emailService)
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Employee::chunk(100, function ($users) {
                foreach ($users as $user) {
                    $this->emailService->sendEmails($user);
                }
            });
    }
}
