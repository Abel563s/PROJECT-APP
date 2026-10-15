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

it('applies the dashboard top filters consistently to returned dashboard data', function () {
    Project::create([
        'project_name' => 'Alpha Filter Match',
        'project_code' => 'DASH-ALPHA',
        'status' => 'completed',
        'project_type' => 'Building',
        'delivery_method' => 'DB',
        'project_client' => 'Client A',
        'consultancy_sector' => 'Private',
        'contract_budget' => '1000.00',
        'variation' => '0.00',
        'total_allowable_cost' => '1000.00',
        'cost_at_completion' => '900.00',
        'baseline_start_date' => now()->subDays(10)->toDateString(),
        'baseline_finish_date' => now()->toDateString(),
        'actual_start_date' => now()->subDays(10)->toDateString(),
        'actual_finish_date' => now()->toDateString(),
        'closing_status' => 'FA Received',
    ]);

    Project::create([
        'project_name' => 'Beta Filter Excluded',
        'project_code' => 'DASH-BETA',
        'status' => 'in progress',
        'project_type' => 'Infrastructure',
        'delivery_method' => 'DBB',
        'project_client' => 'Client B',
        'consultancy_sector' => 'Government',
        'contract_budget' => '2000.00',
        'variation' => '0.00',
        'total_allowable_cost' => '2000.00',
        'cost_at_completion' => '1800.00',
        'baseline_start_date' => now()->subDays(10)->toDateString(),
        'baseline_finish_date' => now()->addDays(10)->toDateString(),
        'actual_start_date' => now()->subDays(5)->toDateString(),
        'closing_status' => 'Not Completed',
    ]);

    $request = Request::create('/admin/dashboard/data', 'GET', [
        'search' => 'Alpha',
        'sector' => 'Building',
        'delivery_method' => 'DB',
        'consultant_type' => 'Private',
        'lifecycle_status' => 'completed',
    ]);

    $response = (new DashboardController())->data($request);
    $payload = json_decode($response->getContent(), true);

    expect($payload['error'] ?? false)->toBeFalse()
        ->and($payload['total'])->toBe(1)
        ->and($payload['all_projects'])->toHaveCount(1)
        ->and($payload['all_projects'][0]['project_code'])->toBe('DASH-ALPHA')
        ->and($payload['executive_kpis']['total_projects']['total'])->toBe(1)
        ->and($payload['analytics']['project_status']['total'])->toBe(1)
        ->and($payload['comparative_analytics']['time_by_sector']['series'][0]['counts'][0])->toBe(1);
});
