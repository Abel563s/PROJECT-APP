<?php

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('calculates issue priorities from the resolution date and status', function () {
    Carbon::setTestNow('2026-10-23 12:00:00');

    try {
        expect((new Issue(['status' => 'Open', 'planned_resolution_date' => '2026-10-22']))->days_left)->toBe(-1)
            ->and((new Issue(['status' => 'Open', 'planned_resolution_date' => '2026-10-22']))->priority)->toBe('Overdue')
            ->and((new Issue(['status' => 'Open', 'planned_resolution_date' => '2026-10-29']))->priority)->toBe('High')
            ->and((new Issue(['status' => 'Open', 'planned_resolution_date' => '2026-11-05']))->priority)->toBe('Medium')
            ->and((new Issue(['status' => 'Open', 'planned_resolution_date' => '2026-11-06']))->priority)->toBe('Low')
            ->and((new Issue(['issue_closed_date' => '2026-10-23', 'planned_resolution_date' => '2026-10-22']))->priority)->toBe('Done')
            ->and((new Issue(['status' => 'Open']))->priority)->toBe('Unscheduled');
    } finally {
        Carbon::setTestNow();
    }
});

it('derives and persists issue status from the planned and closed dates', function () {
    $issue = Issue::create([
        'issue' => 'Date-driven status issue',
        'date' => '2026-11-03',
        'status' => 'Closed',
    ]);

    expect($issue->status)->toBe('Raised')
        ->and($issue->getRawOriginal('status'))->toBe('Raised');

    $issue->planned_resolution_date = '2026-11-10';
    $issue->save();
    $issue->refresh();

    expect($issue->status)->toBe('Open')
        ->and($issue->getRawOriginal('status'))->toBe('Open');

    $issue->issue_closed_date = '2026-11-12';
    $issue->save();
    $issue->refresh();

    expect($issue->status)->toBe('Closed')
        ->and($issue->getRawOriginal('status'))->toBe('Closed');

    $issue->issue_closed_date = null;
    $issue->planned_resolution_date = null;
    $issue->save();
    $issue->refresh();

    expect($issue->status)->toBe('Raised')
        ->and($issue->getRawOriginal('status'))->toBe('Raised');
});

it('filters the project issue table by priority and displays the requested columns', function () {
    Carbon::setTestNow('2026-10-23 12:00:00');

    try {
        $coordinator = User::factory()->create(['role' => 'user']);
        $project = Project::create([
            'project_name' => 'Issue Filter Project',
            'project_code' => 'ISSUE-FILTER',
        ]);
        $project->coordinators()->attach($coordinator);

        Issue::create([
            'project_id' => $project->id,
            'created_by' => $coordinator->id,
            'issue_type' => 'Design',
            'issue' => 'Due soon',
            'impact' => 'High',
            'status' => 'Open',
            'raised_date' => '2026-10-20',
            'planned_resolution_date' => '2026-10-29',
            'date' => '2026-10-20',
        ]);
        Issue::create([
            'project_id' => $project->id,
            'created_by' => $coordinator->id,
            'issue_type' => 'Finance',
            'issue' => 'Due later',
            'impact' => 'Low',
            'status' => 'Open',
            'raised_date' => '2026-10-21',
            'planned_resolution_date' => '2026-11-10',
            'date' => '2026-10-21',
        ]);

        $this->actingAs($coordinator)
            ->get(route('admin.projects.issues.manage', [$project, 'priority' => 'High']))
            ->assertOk()
            ->assertSee('Due soon')
            ->assertDontSee('Due later')
            ->assertSee('Raised')
            ->assertSee('Planned Action Date')
            ->assertSee('Priority')
            ->assertSee('Total Issues')
            ->assertSee('Done')
            ->assertSee('Overdue')
            ->assertSee('High')
            ->assertSee('Medium')
            ->assertSee('Low')
            ->assertSee('grid-cols-7', false)
            ->assertDontSee('Filter Issues')
            ->assertDontSee('Narrow the list by issue details, status, and priority')
            ->assertSee('Data Date')
            ->assertDontSee('Raised By')
            ->assertDontSee('Raised From')
            ->assertDontSee('Raised To')
            ->assertDontSee('Resolution From')
            ->assertDontSee('Resolution To')
            ->assertDontSee('>In Progress</option>', false)
            ->assertDontSee('>Cancelled</option>', false);

        $issue = Issue::where('issue', 'Due soon')->firstOrFail();
        $this->get(route('admin.projects.issues.show', $issue).'?partial=1')
            ->assertOk()
            ->assertSee('Days Left')
            ->assertSee('6 days left');

        $this->get(route('admin.projects.issues.create-modal', $project).'?partial=1')
            ->assertOk()
            ->assertDontSee('name="status"', false)
            ->assertSee('data-issue-status', false)
            ->assertSee('name="issue_closed_date"', false)
            ->assertDontSee('value="In Progress"', false)
            ->assertDontSee('value="Cancelled"', false);

        $this->get(route('admin.projects.issues.edit', $issue).'?partial=1')
            ->assertOk()
            ->assertDontSee('name="status"', false)
            ->assertSee('data-issue-status', false)
            ->assertSee('name="issue_closed_date"', false)
            ->assertDontSee('value="In Progress"', false)
            ->assertDontSee('value="Cancelled"', false);
    } finally {
        Carbon::setTestNow();
    }
});

it('does not allow project coordinators to delete issues', function () {
    $coordinator = User::factory()->create(['role' => 'user']);
    $project = Project::create([
        'project_name' => 'Issue Delete Project',
        'project_code' => 'ISSUE-DELETE',
    ]);
    $project->coordinators()->attach($coordinator);
    $issue = Issue::create([
        'project_id' => $project->id,
        'created_by' => $coordinator->id,
        'issue' => 'Coordinator issue',
        'date' => '2026-10-23',
    ]);

    $this->actingAs($coordinator)
        ->get(route('admin.projects.issues.manage', $project))
        ->assertOk()
        ->assertDontSee(route('admin.projects.issues.destroy', $issue), false);

    $this->actingAs($coordinator)
        ->delete(route('admin.projects.issues.destroy', $issue))
        ->assertForbidden();

    $this->assertDatabaseHas('issues', ['id' => $issue->id]);
});

it('shows status and priority issue counts in the project registry and filters by priority', function () {
    Carbon::setTestNow('2026-10-23 12:00:00');

    try {
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::create([
            'project_name' => 'Registry Priority Project',
            'project_code' => 'REG-PRIORITY',
            'status' => 'in progress',
        ]);

        Issue::create([
            'project_id' => $project->id,
            'issue' => 'Open overdue item',
            'status' => 'Open',
            'planned_resolution_date' => '2026-10-22',
        ]);
        Issue::create([
            'project_id' => $project->id,
            'issue' => 'Closed issue',
            'planned_resolution_date' => '2026-10-20',
            'issue_closed_date' => '2026-10-22',
        ]);
        Issue::create([
            'project_id' => $project->id,
            'issue' => 'High priority item',
            'status' => 'Open',
            'planned_resolution_date' => '2026-10-29',
        ]);
        Issue::create([
            'project_id' => $project->id,
            'issue' => 'Medium priority item',
            'status' => 'Open',
            'planned_resolution_date' => '2026-11-05',
        ]);
        Issue::create([
            'project_id' => $project->id,
            'issue' => 'Low priority item',
            'status' => 'Open',
            'planned_resolution_date' => '2026-11-10',
        ]);

        $registryResponse = $this->actingAs($admin)
            ->get(route('admin.projects.issues.index'))
            ->assertOk()
            ->assertSee('Status')
            ->assertSee('Raised')
            ->assertSee('Priority')
            ->assertSee('Overdue')
            ->assertSee('High')
            ->assertSee('Medium')
            ->assertSee('Low')
            ->assertDontSee('>In Progress</th>', false);
        $registryResponse->assertViewHas('projects', function ($projects) {
            $listedProject = $projects->first();

            return $listedProject->total_issues === 5
                && $listedProject->raised_issues === 0
                && $listedProject->open_issues === 4
                && $listedProject->closed_issues === 1
                && $listedProject->overdue_issues === 1
                && $listedProject->high_priority_issues === 1
                && $listedProject->medium_priority_issues === 1
                && $listedProject->low_priority_issues === 1;
        });

        $priorityResponse = $this->get(route('admin.projects.issues.index', ['priority' => 'High']))
            ->assertOk()
            ->assertSee('REG-PRIORITY');
        $priorityResponse->assertViewHas('projects', fn ($projects) => $projects->count() === 1);
    } finally {
        Carbon::setTestNow();
    }
});

it('shows impact counts in the project registry and filters by impact', function () {
    Carbon::setTestNow('2026-10-23 12:00:00');

    try {
        $admin = User::factory()->create(['role' => 'admin']);

        $project = Project::create([
            'project_name' => 'Impact Project',
            'project_code' => 'IMPACT-TEST',
            'status' => 'in progress',
        ]);
        Issue::create([
            'project_id' => $project->id,
            'issue' => 'High impact item',
            'status' => 'Open',
            'impact' => 'High',
            'planned_resolution_date' => '2026-10-29',
        ]);
        Issue::create([
            'project_id' => $project->id,
            'issue' => 'Low impact item',
            'status' => 'Open',
            'impact' => 'Low',
            'planned_resolution_date' => '2026-11-10',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.projects.issues.index'))
            ->assertOk()
            ->assertSee('Impact')
            ->assertViewHas('projects', function ($projects) {
                $listed = $projects->first();

                return $listed
                    && $listed->high_impact_issues === 1
                    && $listed->medium_impact_issues === 0
                    && $listed->low_impact_issues === 1;
            });

        Project::create([
            'project_name' => 'Other Impact Project',
            'project_code' => 'IMPACT-TEST-2',
            'status' => 'in progress',
        ])->issues()->create([
            'issue' => 'Medium impact item',
            'status' => 'Open',
            'impact' => 'Medium',
            'planned_resolution_date' => '2026-11-05',
        ]);

        $this->get(route('admin.projects.issues.index', ['impact' => 'High']))
            ->assertOk()
            ->assertSee('IMPACT-TEST')
            ->assertDontSee('IMPACT-TEST-2')
            ->assertViewHas('projects', fn ($projects) => $projects->count() === 1);
    } finally {
        Carbon::setTestNow();
    }
});

it('shows only in-progress projects by default on the issue registry', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $inProgressProject = Project::create([
        'project_name' => 'In Progress Issue Project',
        'project_code' => 'ISSUE-IN-PROGRESS',
        'status' => 'in progress',
    ]);
    Project::create([
        'project_name' => 'Completed Issue Project',
        'project_code' => 'ISSUE-COMPLETED',
        'status' => 'completed',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.projects.issues.index'))
        ->assertOk()
        ->assertSee('ISSUE-IN-PROGRESS')
        ->assertDontSee('ISSUE-COMPLETED')
        ->assertViewHas('projects', fn ($projects) => $projects->count() === 1);

    $this->get(route('admin.projects.issues.index', ['status' => 'all']))
        ->assertOk()
        ->assertSee('ISSUE-IN-PROGRESS')
        ->assertSee('ISSUE-COMPLETED');
});
