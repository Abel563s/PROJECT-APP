<?php

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectRisk;
use App\Models\ProjectRiskPool;
use App\Models\EotCategory;
use App\Models\EotEntry;
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
    $baseOfRisk = EotCategory::create(['name' => 'Design Delay', 'display_order' => 1, 'is_active' => true]);
    $eot = EotEntry::create([
        'eot_id' => '1.01',
        'eot_category_id' => $baseOfRisk->id,
        'detail_reason' => 'Late design approval',
    ]);

        $this->actingAs($admin)
        ->post(route('admin.projects.risk.store', $project), [
            'activity' => 'Awarding the subcontract',
            'risk_type' => 'Threat',
            'risk_name' => 'Late subcontract award',
            'risk_description' => 'A late award may delay the critical path.',
            'likelihood' => 4,
            'severity' => 5,
            'intent_to_claim' => '1',
            'eot_category_id' => $baseOfRisk->id,
            'eot_entry_id' => $eot->id,
            'most_likely_quality_impact' => 5,
            'resolution_by' => 'Project Manager',
            'impact_date' => '2026-11-03',
            'materialized_date' => '2026-11-04',
            'action_status' => 'Issue',
        ])
        ->assertRedirect(route('admin.projects.risk.manage', $project));

    $risk = ProjectRisk::firstOrFail();
    expect($risk->risk_score)->toBe(20)
        ->and($risk->risk_level)->toBe('Extreme')
        ->and($risk->project_id)->toBe($project->id)
        ->and($risk->eot_entry_id)->toBe($eot->id)
        ->and($risk->most_likely_quality_impact)->toBe(5)
        ->and($risk->resolution_by)->toBe('Project Manager')
        ->and($risk->materialized_date->format('Y-m-d'))->toBe('2026-11-04')
        ->and($risk->date_last_updated)->not->toBeNull();

    $this->get(route('admin.projects.risk.manage', $project))
        ->assertOk()
        ->assertSee('Late subcontract award')
        ->assertSee('Extreme');

    $this->get(route('admin.projects.risk.view-modal', $risk))
        ->assertOk()
        ->assertSee($project->project_code)
        ->assertSee($project->project_name)
        ->assertSee('Last updated')
        ->assertSee('Late design approval')
        ->assertSee('Most Likely Quality Impact (Days)')
        ->assertSee('Resolution by')
        ->assertSee('Assumed Impact Date')
        ->assertSee('Materialized Date')
        ->assertDontSee('Date Last Updated')
        ->assertDontSee('Risk Owner');

    $this->get(route('admin.projects.risk.edit-modal', $risk))
        ->assertOk()
        ->assertSee($project->project_code)
        ->assertSee($project->project_name)
        ->assertSee('Last updated')
        ->assertSee('Late design approval')
        ->assertSee('Materialized Date')
        ->assertDontSee('Date Last Updated')
        ->assertDontSee('Risk Owner');
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
        'action_status' => 'Risk',
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
        'action_status' => 'Resolved',
    ]);

        $this->actingAs($admin)
            ->get(route('admin.projects.risk.index'))
            ->assertOk()
            ->assertSee('Risk Status')
            ->assertSee('OPEN-RISK')
            ->assertSee('CLOSED-RISK');

        $this->get(route('admin.projects.risk.index', ['risk_status' => 'Risk']))
            ->assertOk()
            ->assertViewHas('risks', fn ($risks) => count($risks) === 1)
            ->assertViewHas('risks', fn ($risks) => $risks[0]['project_code'] === 'OPEN-RISK');

        $this->get(route('admin.projects.risk.index', ['risk_status' => 'Resolved']))
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
        ->assertSee('RISK-100')
        ->assertSee('Risk Dashboard Project')
        ->assertSee('Last updated')
        ->assertSee('Risk Type')
        ->assertSee('Pool Risk Category')
        ->assertSee('Base of Risk')
        ->assertSee('EOT ID')
        ->assertSee('Detail Reason of EOT')
        ->assertSee('Materialized Date')
        ->assertSee('Most Likely Quality Impact (Days)')
        ->assertDontSee('Risk Owner');
});

it('filters repository categories and risks by the selected risk type', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $project = Project::create([
        'custom_id' => 'PRJ-0103',
        'project_code' => 'RISK-POOL-TYPE',
        'project_name' => 'Risk Pool Type Project',
        'status' => 'in progress',
    ]);

    ProjectRiskPool::create([
        'risk_id' => '1.01',
        'risk_type' => 'Opportunity',
        'risk_category' => 'Financial Risks',
        'specific_risk' => 'Cash flow improvement',
    ]);
    ProjectRiskPool::create([
        'risk_id' => '5.01',
        'risk_type' => 'Threat',
        'risk_category' => 'Safety Risks',
        'specific_risk' => 'Workplace accident',
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk.pool-risks', [$project, 'risk_type' => 'Opportunity']))
        ->assertOk()
        ->assertExactJson(['Financial Risks']);

    $this->getJson(route('admin.projects.risk.pool-risks', [$project, 'risk_type' => 'Threat', 'category' => 'Safety Risks']))
        ->assertOk()
        ->assertJsonPath('0.risk_id', '5.01')
        ->assertJsonPath('0.risk_type', 'Threat');
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
        'action_status' => 'Risk',
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
        'action_status' => 'Resolved',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.projects.risk.manage', $project))
        ->assertOk()
        ->assertSee('Search Risks')
        ->assertViewHas('riskCategories');

    $this->get(route('admin.projects.risk.manage', [$project, 'action_status' => 'Risk']))
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
        ->assertOk();

    $this->post(route('admin.projects.risk.store', $project), [
        'activity' => 'Coordination task',
        'risk_type' => 'Threat',
        'risk_name' => 'Coordinator Created Risk',
        'risk_description' => 'A risk created by coordinator',
        'likelihood' => 3,
        'severity' => 3,
        'intent_to_claim' => '0',
        'action_status' => 'Risk',
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
        'activity' => 'Updated task',
        'risk_type' => 'Threat',
        'risk_name' => 'Updated Risk Name',
        'risk_description' => 'Updated description',
        'likelihood' => 4,
        'severity' => 4,
        'intent_to_claim' => '0',
        'action_status' => 'Issue',
        'most_likely_cost_impact' => 0,
    ])
        ->assertRedirect(route('admin.projects.risk.manage', $risk->project));

    $risk->refresh();
    expect($risk->risk_name)->toBe('Updated Risk Name')
        ->and($risk->risk_score)->toBe(16);
});
