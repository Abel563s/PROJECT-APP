<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProjectProgressUpdate;
use Illuminate\Support\Facades\DB;

$updates = ProjectProgressUpdate::select(
        'project_progress_updates.project_id',
        'projects.project_code',
        'projects.project_name',
        'project_progress_updates.progress_planned',
        'project_progress_updates.progress_actual',
        'project_progress_updates.created_at as latest_update'
    )
    ->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
    ->whereIn('projects.status', ['in progress', 'in_progress'])
    ->orderByDesc('project_progress_updates.created_at')
    ->get()
    ->unique('project_id')
    ->values();

echo "Latest Progress Updates for Active Projects (using new query):\n";
echo "-------------------------------------------------------------\n";
foreach ($updates as $update) {
    $remaining = max(0, $update->progress_planned - $update->progress_actual);
    echo "{$update->project_code} - {$update->project_name}:\n";
    echo "  Planned: {$update->progress_planned}\n";
    echo "  Actual: {$update->progress_actual}\n";
    echo "  Remaining: {$remaining}\n";
    echo "  Date: {$update->latest_update}\n\n";
}
