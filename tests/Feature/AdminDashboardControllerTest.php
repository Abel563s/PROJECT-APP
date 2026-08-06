<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

it('returns dashboard data without error when contract budget is zero', function () {

    Project::create([
        'project_name' => 'Zero Budget Project',
        'project_code' => 'TEST-001',
        'status' => 'completed',
        'project_type' => 'Building',
        'delivery_method' => 'DB',
        'project_client' => 'Client A',
        'consultancy_sector' => 'Private',
        'contract_budget' => '0.00',
        'revised_budget_amount' => '1000.00',
        'variation' => '0.00',
        'total_allowable_cost' => '0.00',
        'cost_at_completion' => '0.00',
        'baseline_start_date' => now()->subDays(10)->toDateString(),
        'baseline_finish_date' => now()->toDateString(),
        'actual_start_date' => now()->subDays(10)->toDateString(),
        'actual_finish_date' => now()->toDateString(),
        'closing_status' => 'FA Received',
    ]);

    $controller = new DashboardController();
    $response = $controller->data(new Request());

    expect($response->getStatusCode())->toBe(200);

    $payload = json_decode($response->getContent(), true);

    expect($payload['error'] ?? false)->toBeFalse();
    expect($payload['total'])->toBe(1);
});
