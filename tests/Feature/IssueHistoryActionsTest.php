<?php

use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows edit and admin-only delete actions on the issue history page', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    $coordinator = User::factory()->create(['role' => 'user', 'is_active' => true]);

    $project = Project::create(['project_name' => 'Hist Project', 'project_code' => 'HIST-001']);
    Issue::create([
        'project_id' => $project->id,
        'issue' => 'History issue',
        'issue_type' => 'Design',
        'impact' => 'High',
        'status' => 'Open',
        'raised_date' => '2026-10-20',
        'date' => '2026-10-20',
        'created_by' => $admin->id,
    ]);

    $adminHtml = $this->actingAs($admin)
        ->get(route('admin.projects.issues.history'))
        ->assertOk()
        ->assertSee('Raised')
        ->getContent();

    $coordHtml = $this->actingAs($coordinator)
        ->get(route('admin.projects.issues.history'))
        ->assertOk()
        ->getContent();

    $editBtn = 'onclick="openEditModal(';
    $deleteConfirm = 'Are you sure you want to delete this issue?';

    expect(strpos($adminHtml, $editBtn))->not->toBeFalse()
        ->and(strpos($adminHtml, $deleteConfirm))->not->toBeFalse()
        ->and(strpos($coordHtml, $editBtn))->toBeFalse()
        ->and(strpos($coordHtml, $deleteConfirm))->toBeFalse();
});
