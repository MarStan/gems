<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\SyncMeetingsService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncMeetings extends Command
{
    protected $signature = 'meetings:sync';

    protected $description = 'Sync meetings';

    public function __construct(
        private SyncMeetingsService $syncService,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        Log::info('Start meeting sync process');

        Employee::chunk(100, function ($employees) {
                foreach ($employees as $employee) {
                    $this->syncService->sync($employee);
                }
            });

        Log::info('End meeting sync process');
        return 0;
    }
}
