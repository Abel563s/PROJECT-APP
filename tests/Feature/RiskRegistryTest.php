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

it('filters the risk registry by risk status', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $openProject = Project::create([
        'project_code' => 'OPEN-RISK',
        'project_name' => 'Open Risk Project',
        'status' => 'in progress',
    ]);
    ProjectRisk::create([
        'project_id' => $openProject->id,
        'risk_category' => 'Operational',
        'risk_type' => 'Threat',
        'activity' => 'Test activity',
        'risk_name' => 'Open risk',
        'risk_description' => 'An open risk item',
        'likelihood' => 2,
        'severity' => 2,
        'action_status' => 'Open',
    ]);

    $closedProject = Project::create([
        'project_code' => 'CLOSED-RISK',
        'project_name' => 'Closed Risk Project',
        'status' => 'in progress',
    ]);
    ProjectRisk::create([
        'project_id' => $closedProject->id,
        'risk_category' => 'Operational',
        'risk_type' => 'Threat',
        'activity' => 'Test activity',
        'risk_name' => 'Closed risk',
        'risk_description' => 'A closed risk item',
        'likelihood' => 2,
        'severity' => 2,
        'action_status' => 'Closed',
    ]);

        $this->actingAs($admin)
            ->get(route('admin.projects.risk.index'))
            ->assertOk()
            ->assertSee('Risk Status')
            ->assertSee('OPEN-RISK')
            ->assertSee('CLOSED-RISK');

        $this->get(route('admin.projects.risk.index', ['risk_status' => 'Open']))
            ->assertOk()
            ->assertViewHas('risks', fn ($risks) => count($risks) === 1)
            ->assertViewHas('risks', fn ($risks) => $risks[0]['project_code'] === 'OPEN-RISK');

        $this->get(route('admin.projects.risk.index', ['risk_status' => 'Closed']))
            ->assertOk()
            ->assertViewHas('risks', fn ($risks) => count($risks) === 1)
            ->assertViewHas('risks', fn ($risks) => $risks[0]['project_code'] === 'CLOSED-RISK');
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
        ->assertSee('Extreme')
        ->assertSee('Low')
        ->assertSee('New Risk');

    $this->get(route('admin.projects.risk.create-modal', $project))
        ->assertOk()
        ->assertSee('Risk Category')
        ->assertSee('Project Name');
});

it('filters risks on the manage page by status, category, type, and level', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $project = Project::create([
        'custom_id' => 'PRJ-0101',
        'project_code' => 'RISK-FILTER',
        'project_name' => 'Risk Filter Project',
        'status' => 'in progress',
    ]);

    ProjectRisk::create([
        'project_id' => $project->id,
        'risk_category' => 'Operational',
        'risk_type' => 'Threat',
        'activity' => 'Activity 1',
        'risk_name' => 'Open Operational Threat',
        'risk_description' => 'Open risk',
        'likelihood' => 4,
        'severity' => 5,
        'risk_urgency' => 'High',
        'owner_name' => 'John Doe',
        'action_status' => 'Open',
    ]);

    ProjectRisk::create([
        'project_id' => $project->id,
        'risk_category' => 'Financial',
        'risk_type' => 'Opportunity',
        'activity' => 'Activity 2',
        'risk_name' => 'Closed Financial Opportunity',
        'risk_description' => 'Closed risk',
        'likelihood' => 1,
        'severity' => 1,
        'risk_urgency' => 'Low',
        'owner_name' => 'Jane Smith',
        'action_status' => 'Closed',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.projects.risk.manage', $project))
        ->assertOk()
        ->assertSee('Search Risks')
        ->assertViewHas('riskCategories');

    $this->get(route('admin.projects.risk.manage', [$project, 'action_status' => 'Open']))
        ->assertOk()
        ->assertViewHas('risks', fn ($risks) => $risks->count() === 1)
        ->assertViewHas('risks', fn ($risks) => $risks[0]->risk_name === 'Open Operational Threat');

    $this->get(route('admin.projects.risk.manage', [$project, 'risk_category' => 'Financial']))
        ->assertOk()
        ->assertViewHas('risks', fn ($risks) => $risks->count() === 1)
        ->assertViewHas('risks', fn ($risks) => $risks[0]->risk_name === 'Closed Financial Opportunity');

    $this->get(route('admin.projects.risk.manage', [$project, 'risk_level' => 'Extreme']))
        ->assertOk()
        ->assertViewHas('risks', fn ($risks) => $risks->count() === 1)
        ->assertViewHas('risks', fn ($risks) => $risks[0]->risk_name === 'Open Operational Threat');
});

it('allows Project Coordinator to see New Risk button and create, view, edit risks', function () {
    $coordinator = User::factory()->create(['role' => 'user']);
    $project = Project::create([
        'custom_id' => 'PRJ-0102',
        'project_code' => 'RISK-COORD',
        'project_name' => 'Risk Coordinator Project',
        'status' => 'in progress',
    ]);
    $project->coordinators()->attach($coordinator->id);

    $this->actingAs($coordinator)
        ->get(route('admin.projects.risk.manage', $project))
        ->assertOk()
        ->assertSee('New Risk')
        ->assertSee('Total Risks');

    $this->get(route('admin.projects.risk.create-modal', $project))
        ->assertOk()
        ->assertSee('Risk Category');

    $this->post(route('admin.projects.risk.store', $project), [
        'risk_category' => 'Operational',
        'activity' => 'Coordination task',
        'risk_type' => 'Threat',
        'risk_name' => 'Coordinator Created Risk',
        'risk_description' => 'A risk created by coordinator',
        'likelihood' => 3,
        'severity' => 3,
        'project_impact' => 'Time',
        'intent_to_claim' => '1',
        'action_status' => 'Open',
    ])
        ->assertRedirect(route('admin.projects.risk.manage', $project));

    $risk = ProjectRisk::where('risk_name', 'Coordinator Created Risk')->firstOrFail();
    expect($risk->created_by)->toBe($coordinator->id)
        ->and($risk->risk_score)->toBe(9)
        ->and($risk->risk_level)->toBe('Medium');

    $this->get(route('admin.projects.risk.view-modal', $risk))
        ->assertOk()
        ->assertSee('Coordinator Created Risk');

    $this->get(route('admin.projects.risk.edit-modal', $risk))
        ->assertOk()
        ->assertSee('Coordinator Created Risk');

    $this->put(route('admin.projects.risk.update', $risk), [
        'risk_category' => 'Operational',
        'activity' => 'Updated task',
        'risk_type' => 'Threat',
        'risk_name' => 'Updated Risk Name',
        'risk_description' => 'Updated description',
        'likelihood' => 4,
        'severity' => 4,
        'project_impact' => 'Time',
        'intent_to_claim' => '1',
        'action_status' => 'Inprogress',
        'most_likely_cost_impact' => 0,
    ])
        ->assertRedirect(route('admin.projects.risk.manage', $risk->project));

    $risk->refresh();
    expect($risk->risk_name)->toBe('Updated Risk Name')
        ->and($risk->risk_score)->toBe(16);
});
