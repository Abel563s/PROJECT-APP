<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProjectProgressUpdate;
use Illuminate\Support\Facades\DB;

$data = ProjectProgressUpdate::select(
    'project_progress_updates.project_id',
    'projects.project_code',
    'projects.project_name',
    DB::raw('AVG(project_progress_updates.progress_planned) as avg_planned'),
    DB::raw('AVG(project_progress_updates.progress_actual) as avg_actual')
)
->join('projects', 'project_progress_updates.project_id', '=', 'projects.id')
->whereIn('projects.status', ['in progress', 'in_progress'])
->groupBy('project_progress_updates.project_id', 'projects.project_code', 'projects.project_name')
->get()
->map(function ($item) {
    return [
        'code' => $item->project_code,
        'name' => $item->project_name,
        'planned' => round($item->avg_planned, 1),
        'actual' => round($item->avg_actual, 1),
    ];
});

echo "Schedule Performance Data:\n";
echo "-------------------------\n";
foreach ($data as $item) {
    echo "{$item['code']} - {$item['name']}: Planned={$item['planned']}, Actual={$item['actual']}, Remaining=" . max(0, $item['planned'] - $item['actual']) . "\n";
}
echo "\nTotal projects: " . $data->count() . "\n";
