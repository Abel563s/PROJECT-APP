<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class BackfillProjectCompletedStatus extends Command
{
    protected $signature = 'app:backfill-project-completed-status';

    protected $description = 'Backfill project status to completed where actual_finish_date is set';

    public function handle()
    {
        $updated = Project::whereNotNull('actual_finish_date')
            ->where('status', '!=', 'completed')
            ->update(['status' => 'completed']);

        $this->info("Updated {$updated} project(s) to completed.");
    }
}
