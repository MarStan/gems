<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\SyncService;
use Illuminate\Console\Command;

class SyncMeetings extends Command
{
    protected $signature = 'meetings:sync';

    protected $description = 'Sync meetings';

    public function __construct(
        private SyncService $syncService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        Employee::latest('id')
            ->chunk(100, function ($users) {
                foreach ($users as $user) {
                    $this->syncService->sync($user);
                }
            });

        return 0;
    }
}
