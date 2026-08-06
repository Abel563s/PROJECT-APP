<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores budget at completion when creating a project', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $this->actingAs($admin);

    $response = $this->post(route('admin.projects.store'), [
        'project_name' => 'Budget Test Project',
        'project_code' => 'BGT-001',
        'project_type' => 'Building',
        'delivery_method' => 'DB',
        'contract_budget' => 1200000,
        'variation' => 50000,
        'supplementary' => 25000,
        'budget_at_completion' => 1275000,
        'total_allowable_cost' => 1100000,
        'cost_at_completion' => 1150000,
    ]);

    $response->assertRedirect(route('admin.projects.index'));
    $this->assertDatabaseHas('projects', [
        'project_name' => 'Budget Test Project',
        'budget_at_completion' => 1275000.0,
    ]);
});
