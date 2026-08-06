<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $controller = new App\Http\Controllers\Admin\DashboardController();
    $request = Illuminate\Http\Request::create('admin/dashboard/data', 'GET');
    $response = $controller->data($request);
    echo $response->getContent();
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
    echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
