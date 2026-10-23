<?php

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectRisk;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the empty risk registry page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.projects.risk.index'))
        ->assertOk()
        ->assertSee('Risk Registry')
        ->assertDontSee('New Risk');
});

it('creates a project risk with a calculated rating', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $project = Project::create([
        'custom_id' => 'PRJ-0099',
        'project_code' => 'RISK-099',
        'project_name' => 'Risk Test Project',
        'status' => 'in progress',
    ]);

    $this->actingAs($admin)
        ->post(route('admin.projects.risk.store', $project), [
            'risk_category' => 'Contractual',
            'activity' => 'Awarding the subcontract',
            'risk_type' => 'Threat',
            'risk_name' => 'Late subcontract award',
            'risk_description' => 'A late award may delay the critical path.',
            'likelihood' => 4,
            'severity' => 5,
            'project_impact' => 'Time & Cost',
            'intent_to_claim' => '1',
            'action_status' => 'Open',
        ])
        ->assertRedirect(route('admin.projects.risk.manage', $project));

    $risk = ProjectRisk::firstOrFail();
    expect($risk->risk_score)->toBe(20)
        ->and($risk->risk_level)->toBe('Extreme')
        ->and($risk->project_id)->toBe($project->id);

    $this->get(route('admin.projects.risk.manage', $project))
        ->assertOk()
        ->assertSee('Late subcontract award')
        ->assertSee('Extreme');
});

it('renders the project risk dashboard and create modal', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $project = Project::create([
        'custom_id' => 'PRJ-0100',
        'project_code' => 'RISK-100',
        'project_name' => 'Risk Dashboard Project',
        'status' => 'in progress',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.projects.risk.manage', $project))
        ->assertOk()
        ->assertSee('Total Risks')
        ->assertSee('Extreme Rating')
        ->assertSee('New Risk');

    $this->get(route('admin.projects.risk.create-modal', $project))
        ->assertOk()
        ->assertSee('Risk Category')
        ->assertSee('Project Name');
});
