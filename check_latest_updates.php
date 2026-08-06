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
        'project_progress_updates.created_at'
    )
    ->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
    ->whereIn('projects.status', ['in progress', 'in_progress'])
    ->orderBy('project_progress_updates.project_id')
    ->orderByDesc('project_progress_updates.created_at')
    ->get()
    ->groupBy('project_id');

echo "Latest Progress Updates for Active Projects:\n";
echo "--------------------------------------------\n";
foreach ($updates as $projectId => $projectUpdates) {
    $latest = $projectUpdates->first();
    $remaining = max(0, $latest->progress_planned - $latest->progress_actual);
    echo "{$latest->project_code} - {$latest->project_name}:\n";
    echo "  Planned: {$latest->progress_planned}\n";
    echo "  Actual: {$latest->progress_actual}\n";
    echo "  Remaining: {$remaining}\n";
    echo "  Date: {$latest->created_at}\n\n";
}
