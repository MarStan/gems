<?php

namespace App\Console\Commands;

use App\Models\CalendarUser;
use App\Services\SyncService;
use Illuminate\Console\Command;

class Sync extends Command
{
    protected $signature = 'sync';

    protected $description = 'Sync database';

    public function __construct(
        private SyncService $syncService,
    )
    {
        parent::__construct();
    }

    public function handle(): int
    {
        CalendarUser::latest('id')
            ->where('is_usergems_employee', true)
            ->chunk(100, function($users) {
                foreach ($users as $user) {
                    $this->syncService->sync($user);
                }
            });

        return 0;
    }
}
