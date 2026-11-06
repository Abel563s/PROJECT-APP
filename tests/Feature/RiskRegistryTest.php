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
    ProjectRiskPool::create([
        'risk_id' => '4.01',
        'risk_type' => 'Threat',
        'risk_category' => 'Operational',
        'specific_risk' => 'Late subcontract award',
        'detailed_risk_explanation' => 'A late subcontract award may delay the critical path.',
    ]);
    $eot = EotEntry::create([
        'eot_id' => '1.01',
        'eot_category_id' => $baseOfRisk->id,
        'detail_reason' => 'Late design approval',
    ]);

        $this->actingAs($admin)
        ->post(route('admin.projects.risk.store', $project), [
            'activity' => 'Awarding the subcontract',
            'risk_type' => 'Threat',
            'risk_category' => 'Operational',
            'pool_risk_category' => 'Operational',
            'pool_risk_id' => '4.01',
            'risk_name' => 'Late subcontract award',
            'risk_description' => 'A late award may delay the critical path.',
            'likelihood' => 4,
            'severity' => 5,
            'intent_to_claim' => '1',
            'eot_category_id' => $baseOfRisk->id,
            'eot_entry_id' => $eot->id,
            'detail_reason_for_eot' => 'Late design approval delayed critical works.',
            'project_case_event_description' => 'The design approval was received after the planned date.',
            'reference_contract_clause_no' => 'Clause 8.4',
            'affected_activities' => 'Foundation and structural works',
            'itc_notification_status' => 'Submitted',
            'itc_notification_date' => '2026-11-02',
            'eot_status' => 'Submitted',
            'eot_submitted_date' => '2026-11-04',
            'eot_response_date' => '2026-11-05',
            'eot_requested_days' => 14,
            'eot_approved_days' => 10,
            'most_likely_quality_impact_description' => 'May reduce durability.',
            'resolution_by' => 'Project Manager',
            'impact_date' => '2026-11-03',
            'materialized_date' => '2026-11-04',
            'last_review_date' => '2026-11-06',
            'owner_name' => 'Risk Lead',
            'action_owner' => 'Action Lead',
            'action_status' => 'Issue',
            'response_strategy' => 'Mitigate',
        ])
        ->assertRedirect(route('admin.projects.risk.manage', $project));

    $risk = ProjectRisk::firstOrFail();
    expect($risk->risk_score)->toBe(20)
        ->and($risk->risk_level)->toBe('Extreme')
        ->and($risk->project_id)->toBe($project->id)
        ->and($risk->eot_entry_id)->toBe($eot->id)
        ->and($risk->detail_reason_for_eot)->toBe('Late design approval delayed critical works.')
        ->and($risk->project_case_event_description)->toBe('The design approval was received after the planned date.')
        ->and($risk->reference_contract_clause_no)->toBe('Clause 8.4')
        ->and($risk->affected_activities)->toBe('Foundation and structural works')
        ->and($risk->itc_notification_status)->toBe('Submitted')
        ->and($risk->itc_notification_date->format('Y-m-d'))->toBe('2026-11-02')
        ->and($risk->eot_status)->toBe('Submitted')
        ->and($risk->eot_submitted_date->format('Y-m-d'))->toBe('2026-11-04')
        ->and($risk->eot_response_date->format('Y-m-d'))->toBe('2026-11-05')
        ->and($risk->eot_requested_days)->toBe(14)
        ->and($risk->eot_approved_days)->toBe(10)
        ->and($risk->most_likely_quality_impact_description)->toBe('May reduce durability.')
        ->and($risk->resolution_by)->toBe('Project Manager')
        ->and($risk->materialized_date->format('Y-m-d'))->toBe('2026-11-04')
        ->and($risk->last_review_date->format('Y-m-d'))->toBe('2026-11-06')
        ->and($risk->owner_name)->toBe('Risk Lead')
        ->and($risk->action_owner)->toBe('Action Lead')
        ->and($risk->date_last_updated)->not->toBeNull()
        ->and($risk->exposure)->toBe(ProjectRisk::calculateExposureDays('2026-11-04', null));

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
        ->assertSee('Risk ID & Sub Category')
        ->assertSee('Project-Specific Risk Statement')
        ->assertSee('Affected Project Objective/ Activity')
        ->assertSee('Most Likely Quality Impact (Description)')
        ->assertSee('Resolution by')
        ->assertSee('Assumed Impact Date')
        ->assertSee('Materialized Date')
        ->assertSee('Last Review Date')
        ->assertSee('Risk Owner')
        ->assertSee('Action Owner')
        ->assertSee('Detail Reason for EOT')
        ->assertSee('Late design approval delayed critical works.')
        ->assertSee('Clause 8.4')
        ->assertSee('ITC/Notification Date')
        ->assertSee('EOT Approved (Days)')
        ->assertDontSee('Date Last Updated');

    $this->get(route('admin.projects.risk.edit-modal', $risk))
        ->assertOk()
        ->assertSee($project->project_code)
        ->assertSee($project->project_name)
        ->assertSee('Last updated')
        ->assertSee('Late design approval')
        ->assertSee('Risk ID & Sub Category')
        ->assertSee('Project-Specific Risk Statement')
        ->assertSee('Affected Project Objective/ Activity')
        ->assertSee('data-step-target="1"', false)
        ->assertSee('data-step-target="4"', false)
        ->assertSee('Update Risk')
        ->assertSee('eot_entry_preview')
        ->assertSee('Project Case Event Description')
        ->assertSee('ITC/Notification')
        ->assertSee('EOT Status')
        ->assertSee('EOT Submitted Date')
        ->assertSee('Materialized Date')
        ->assertSee('Last Review Date')
        ->assertSee('Risk Owner')
        ->assertSee('Action Owner')
        ->assertDontSee('Date Last Updated');
});

it('requires ITC and EOT submission dates only when their statuses are submitted', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $project = Project::create([
        'custom_id' => 'PRJ-0110',
        'project_code' => 'RISK-EOT-DATES',
        'project_name' => 'Risk EOT Dates Project',
        'status' => 'in progress',
    ]);

    $payload = [
        'activity' => 'Submit the claim',
        'risk_type' => 'Threat',
        'risk_description' => 'A delay may affect scheduled work.',
        'likelihood' => 3,
        'severity' => 3,
        'intent_to_claim' => '1',
        'action_status' => 'Risk',
        'itc_notification_status' => 'Submitted',
        'eot_status' => 'Submitted',
    ];

    $this->actingAs($admin)
        ->post(route('admin.projects.risk.store', $project), $payload)
        ->assertSessionHasErrors(['itc_notification_date', 'eot_submitted_date']);

    $this->post(route('admin.projects.risk.store', $project), array_merge($payload, [
        'itc_notification_status' => 'Not Submitted',
        'eot_status' => 'Approved',
    ]))
        ->assertRedirect(route('admin.projects.risk.manage', $project));

    $risk = ProjectRisk::where('project_id', $project->id)->firstOrFail();
    expect($risk->itc_notification_date)->toBeNull()
        ->and($risk->eot_submitted_date)->toBeNull();
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
        ->assertSee('Risk ID & Name')
        ->assertSee('Base of EOT')
        ->assertSee('EOT ID')
        ->assertSee('Materialized Date')
        ->assertSee('Most Likely Quality Impact (Description)')
        ->assertSee('Exposure (Days)')
        ->assertSee('Project Case Event Description')
        ->assertSee('Reference Contract Clause No.')
        ->assertSee('ITC/Notification')
        ->assertSee('Not Submitted')
        ->assertSee('Approved')
        ->assertSee('data-step-target="1"', false)
        ->assertSee('data-step-target="4"', false)
        ->assertSee('Save Risk')
        ->assertSee('eot_entry_preview')
        ->assertDontSee('Most Likely Quality Impact (Days)')
        ->assertDontSee('Risk Name')
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

    \App\Models\RiskCategory::updateOrCreate(
        ['name' => 'Financial Risks'],
        ['risk_type' => null, 'is_active' => true]
    );
    \App\Models\RiskCategory::updateOrCreate(
        ['name' => 'Procurement Risks'],
        ['risk_type' => 'Opportunity', 'is_active' => true]
    );
    \App\Models\RiskCategory::updateOrCreate(
        ['name' => 'Safety Risks'],
        ['risk_type' => 'Threat', 'is_active' => true]
    );

    ProjectRiskPool::create([
        'risk_id' => '1.02',
        'risk_type' => 'Opportunity',
        'risk_category' => 'Financial Risks',
        'specific_risk' => 'Cost optimization',
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
    ProjectRiskPool::create([
        'risk_id' => '1.03',
        'risk_type' => 'Threat',
        'risk_category' => 'Financial Risks',
        'specific_risk' => 'Cost overrun',
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk.pool-risks', [$project, 'risk_type' => 'Opportunity']))
        ->assertOk()
        ->assertExactJson(['Financial Risks', 'Procurement Risks']);

    $this->getJson(route('admin.projects.risk.pool-risks', [$project, 'risk_type' => 'Threat']))
        ->assertOk()
        ->assertExactJson(['Financial Risks', 'Safety Risks']);

    $this->getJson(route('admin.projects.risk.pool-risks', [$project, 'risk_type' => 'Threat', 'category' => 'Financial Risks']))
        ->assertOk()
        ->assertJsonPath('0.risk_id', '1.03')
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
