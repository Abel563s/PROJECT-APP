<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$projects = App\Models\Project::all();
foreach ($projects as $p) {
    echo $p->project_code . ' | baseline: ' . ($p->baseline_start_date ? $p->baseline_start_date->format('Y-m-d') : 'null') . PHP_EOL;
}
