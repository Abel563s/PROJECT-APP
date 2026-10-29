<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the project status select in the create modal with the correct defaults', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    $html = $this->actingAs($admin)
        ->get(route('admin.projects.create', ['partial' => 1]))
        ->assertOk()
        ->getContent();

    expect($html)->toContain('Project Status')
        ->and(strpos($html, 'value="in progress" selected'))->not->toBeFalse()
        ->and(strpos($html, 'value="suspended"'))->not->toBeFalse()
        ->and(preg_match('/value="completed"[^>]*disabled/', $html))->toBe(1);
});

it('stores a manually chosen suspended project status', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    $this->actingAs($admin)->post(route('admin.projects.store'), [
        'project_name' => 'Suspended Status Project',
        'project_code' => 'SSP-001',
        'status' => 'suspended',
    ])->assertRedirect(route('admin.projects.index'));

    $this->assertDatabaseHas('projects', [
        'project_code' => 'SSP-001',
        'status' => 'suspended',
    ]);
});

it('auto-completes the status when an actual finish date is provided', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    $this->actingAs($admin)->post(route('admin.projects.store'), [
        'project_name' => 'Completed Via Finish Date',
        'project_code' => 'CFN-001',
        'status' => 'suspended',
        'actual_finish_date' => '2026-10-20',
    ])->assertRedirect(route('admin.projects.index'));

    $this->assertDatabaseHas('projects', [
        'project_code' => 'CFN-001',
        'status' => 'completed',
    ]);
});

it('updates a project status from suspended to in progress', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    $project = Project::create([
        'project_name' => 'Edit Status Project',
        'project_code' => 'ESP-001',
        'status' => 'suspended',
    ]);

    $this->actingAs($admin)->put(route('admin.projects.update', $project), [
        'project_name' => 'Edit Status Project',
        'status' => 'in progress',
    ])->assertRedirect(route('admin.projects.index'));

    $this->assertDatabaseHas('projects', [
        'project_code' => 'ESP-001',
        'status' => 'in progress',
    ]);
});

it('renders a static (non-clickable) project status pill in the table', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    $html = $this->actingAs($admin)->get(route('admin.projects.index'))->assertOk()->getContent();

    expect(strpos($html, 'toggleStatus'))->toBeFalse()
        ->and(strpos($html, 'Click to toggle status'))->toBeFalse()
        ->and(strpos($html, 'Suspended'))->not->toBeFalse();
});
