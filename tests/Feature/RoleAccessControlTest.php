<?php

use App\Models\Project;
use App\Models\ProjectProgressUpdate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeAccessProject(string $name): Project
{
    return Project::create([
        'project_name' => $name,
        'project_code' => strtoupper(str_replace(' ', '-', $name)),
    ]);
}

function makeAccessProgressUpdate(Project $project): ProjectProgressUpdate
{
    return ProjectProgressUpdate::create([
        'project_id' => $project->id,
        'progress_planned' => 20,
        'progress_actual' => 15,
        'data_date' => '2026-10-15',
    ]);
}

it('allows management to view records but rejects all management writes and access control', function () {
    $management = User::factory()->create(['role' => 'manager']);
    $project = makeAccessProject('Visible Project');
    $update = makeAccessProgressUpdate($project);

    $this->actingAs($management)->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Sign Out')
        ->assertSee('data-logout-form', false);
    $this->actingAs($management)->get(route('admin.projects.index'))->assertOk();
    $this->actingAs($management)->get(route('admin.progress-updates.index'))->assertOk();
    $this->actingAs($management)->get('/admin/users')->assertMethodNotAllowed();
    $this->actingAs($management)->get(route('admin.roles.index'))->assertForbidden();
    $this->actingAs($management)->get(route('admin.projects.edit', $project))->assertForbidden();
    $this->actingAs($management)->put(route('admin.projects.update', $project), [
        'project_name' => 'Changed Project',
    ])->assertForbidden();
    $this->actingAs($management)->put(route('admin.progress-updates.update', $update), [
        'project_id' => $project->id,
        'progress_planned' => 30,
        'progress_actual' => 25,
        'data_date' => '2026-10-15',
    ])->assertForbidden();
});

it('limits coordinators to editing assigned projects and their progress updates', function () {
    $coordinator = User::factory()->create(['role' => 'user']);
    $otherCoordinator = User::factory()->create(['role' => 'user']);
    $assignedProject = makeAccessProject('Assigned Project');
    $unassignedProject = makeAccessProject('Unassigned Project');
    $assignedProject->coordinators()->attach($coordinator);
    $assignedUpdate = makeAccessProgressUpdate($assignedProject);
    $unassignedUpdate = makeAccessProgressUpdate($unassignedProject);

    $this->actingAs($coordinator)->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Sign Out')
        ->assertSee('data-logout-form', false);
    $this->actingAs($coordinator)->get(route('admin.projects.index'))->assertOk();
    $this->actingAs($coordinator)->put(route('admin.projects.update', $assignedProject), [
        'project_name' => 'Updated Assigned Project',
    ])->assertRedirect(route('admin.projects.index'));
    $this->actingAs($coordinator)->put(route('admin.projects.update', $unassignedProject), [
        'project_name' => 'Changed Unassigned Project',
    ])->assertForbidden();
    $this->actingAs($coordinator)->put(route('admin.projects.update', $assignedProject), [
        'project_name' => 'Changed Assignment',
        'coordinator_ids' => [$otherCoordinator->id],
    ])->assertForbidden();
    $this->actingAs($coordinator)->get(route('admin.projects.create'))->assertForbidden();
    $this->actingAs($coordinator)->post(route('admin.progress-updates.store'), [
        'project_id' => $assignedProject->id,
        'progress_planned' => 40,
        'progress_actual' => 35,
        'data_date' => '2026-10-15',
    ])->assertRedirect(route('admin.progress-updates.index'));
    $this->actingAs($coordinator)->post(route('admin.progress-updates.store'), [
        'project_id' => $unassignedProject->id,
        'progress_planned' => 40,
        'progress_actual' => 35,
        'data_date' => '2026-10-15',
    ])->assertForbidden();
    $this->actingAs($coordinator)->put(route('admin.progress-updates.update', $assignedUpdate), [
        'project_id' => $assignedProject->id,
        'progress_planned' => 30,
        'progress_actual' => 25,
        'data_date' => '2026-10-15',
    ])->assertRedirect(route('admin.progress-updates.index'));
    $this->actingAs($coordinator)->put(route('admin.progress-updates.update', $unassignedUpdate), [
        'project_id' => $assignedProject->id,
        'progress_planned' => 30,
        'progress_actual' => 25,
        'data_date' => '2026-10-15',
    ])->assertForbidden();

    $this->actingAs($coordinator)->get(route('admin.roles.index'))->assertForbidden();
    $this->actingAs($coordinator)->get('/admin/users')->assertMethodNotAllowed();
    $this->actingAs($coordinator)->delete(route('admin.progress-updates.destroy', $assignedUpdate))->assertForbidden();
    expect($otherCoordinator->assignedProjects)->toBeEmpty();
});

it('allows admins to create and update multiple coordinator assignments', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $firstCoordinator = User::factory()->create(['role' => 'user']);
    $secondCoordinator = User::factory()->create(['role' => 'user']);

    $this->actingAs($admin)->get('/admin/users')->assertMethodNotAllowed();

    $this->actingAs($admin)->post(route('admin.projects.store'), [
        'project_name' => 'Multi Coordinator Project',
        'coordinator_ids' => [$firstCoordinator->id, $secondCoordinator->id],
    ])->assertRedirect(route('admin.projects.index'));

    $project = Project::where('project_name', 'Multi Coordinator Project')->firstOrFail();
    expect($project->coordinators->modelKeys())->toEqualCanonicalizing([
        $firstCoordinator->id,
        $secondCoordinator->id,
    ]);

    $this->actingAs($admin)->put(route('admin.projects.update', $project), [
        'project_name' => 'Updated Multi Coordinator Project',
        'coordinator_ids' => [$secondCoordinator->id],
    ])->assertRedirect(route('admin.projects.index'));

    expect($project->fresh()->coordinators->modelKeys())->toBe([$secondCoordinator->id]);
    $this->actingAs($firstCoordinator)->put(route('admin.projects.update', $project), [
        'project_name' => 'No Longer Assigned',
    ])->assertForbidden();
    $this->actingAs($secondCoordinator)->put(route('admin.projects.update', $project), [
        'project_name' => 'Still Assigned',
    ])->assertRedirect(route('admin.projects.index'));

    $this->actingAs($admin)->post(route('admin.progress-updates.store'), [
        'project_id' => $project->id,
        'progress_planned' => 50,
        'progress_actual' => 45,
        'data_date' => '2026-10-15',
    ])->assertRedirect(route('admin.progress-updates.index'));
    $update = ProjectProgressUpdate::where('project_id', $project->id)->firstOrFail();
    $this->actingAs($admin)->put(route('admin.progress-updates.update', $update), [
        'project_id' => $project->id,
        'progress_planned' => 60,
        'progress_actual' => 55,
        'data_date' => '2026-10-15',
    ])->assertRedirect(route('admin.progress-updates.index'));
    $this->actingAs($admin)->delete(route('admin.progress-updates.destroy', $update))
        ->assertRedirect(route('admin.progress-updates.index'));
    $this->actingAs($admin)->delete(route('admin.projects.destroy', $project))
        ->assertRedirect(route('admin.projects.index'));
    $this->assertDatabaseMissing('projects', ['id' => $project->id]);

    $rolesResponse = $this->actingAs($admin)->get(route('admin.roles.index'));
    $rolesResponse->assertOk()->assertSee('<details', false)->assertDontSee('<details open', false);
    expect($admin->fresh()->role_label)->toBe('Admin');
    expect($secondCoordinator->fresh()->role_label)->toBe('Project Coordinator');
});

it('allows admins to assign projects while creating or editing coordinators', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $firstProject = makeAccessProject('Coordinator Project One');
    $secondProject = makeAccessProject('Coordinator Project Two');

    $this->actingAs($admin)->get(route('admin.users.create', ['partial' => 1]))
        ->assertOk()
        ->assertSee('name="assigned_project_ids[]"', false)
        ->assertSee($firstProject->project_code)
        ->assertSee($secondProject->project_code);

    $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'New Coordinator',
        'email' => 'new-coordinator@example.com',
        'role' => 'user',
        'is_active' => 1,
        'assigned_project_ids' => [$firstProject->id, $secondProject->id],
    ])->assertRedirect(route('admin.roles.index'));

    $coordinator = User::where('email', 'new-coordinator@example.com')->firstOrFail();
    expect($coordinator->assignedProjects->modelKeys())->toEqualCanonicalizing([
        $firstProject->id,
        $secondProject->id,
    ]);
    $this->actingAs($admin)->get(route('admin.users.edit', ['user' => $coordinator->id, 'partial' => 1]))
        ->assertOk()
        ->assertSee('id="editIdentityModalForm"', false)
        ->assertSee('name="assigned_project_ids[]"', false)
        ->assertSee($firstProject->project_code)
        ->assertSee('checked', false);

    $this->actingAs($admin)->put(route('admin.users.update', $coordinator), [
        'name' => $coordinator->name,
        'email' => $coordinator->email,
        'role' => 'user',
        'is_active' => 1,
        'assigned_project_ids' => [$secondProject->id],
    ])->assertRedirect(route('admin.roles.index'));
    expect($coordinator->fresh()->assignedProjects->modelKeys())->toBe([$secondProject->id]);

    $this->actingAs($admin)->put(route('admin.users.update', $coordinator), [
        'name' => $coordinator->name,
        'email' => $coordinator->email,
        'role' => 'user',
        'is_active' => 0,
    ])->assertRedirect(route('admin.roles.index'));
    expect($coordinator->fresh()->assignedProjects->modelKeys())->toBe([$secondProject->id]);

    $this->actingAs($admin)->put(route('admin.users.update', $coordinator), [
        'name' => $coordinator->name,
        'email' => $coordinator->email,
        'role' => 'manager',
        'is_active' => 1,
    ])->assertRedirect(route('admin.roles.index'));
    expect($coordinator->fresh()->assignedProjects)->toBeEmpty();
});

it('allows admins to create Management and Admin identities from the Access Control flow', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->get(route('admin.users.create', ['partial' => 1]))
        ->assertOk()
        ->assertSee('value="manager">Management</option>', false)
        ->assertSee('value="admin">Admin</option>', false);

    foreach ([
        [
            'identity' => ['name' => 'Management Identity', 'email' => 'management-identity@example.com', 'role' => 'manager'],
            'credentials' => [],
        ],
        [
            'identity' => ['name' => 'Admin Identity', 'email' => 'admin-identity@example.com', 'role' => 'admin'],
            'credentials' => ['password' => 'adminpassword123', 'password_confirmation' => 'adminpassword123'],
        ],
    ] as $scenario) {
        $identity = $scenario['identity'];
        $this->postJson(route('admin.users.store'), [
            ...$identity,
            ...$scenario['credentials'],
            'is_active' => 1,
        ])->assertCreated()
            ->assertJson(['message' => 'User node created successfully.']);

        $this->assertDatabaseHas('users', $identity);

        $createdUser = User::where('email', $identity['email'])->firstOrFail();
        if ($identity['role'] === 'manager') {
            expect($createdUser->must_change_password)->toBeTrue()
                ->and(\Illuminate\Support\Facades\Hash::check('pass1234', $createdUser->password))->toBeTrue();
        } else {
            expect($createdUser->must_change_password)->toBeFalse()
                ->and(\Illuminate\Support\Facades\Hash::check('adminpassword123', $createdUser->password))->toBeTrue();
        }
    }
});

it('requires Management and Project Coordinators to replace the temporary password after login', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    foreach ([
        ['name' => 'First Login Management', 'email' => 'first-login-management@example.com', 'role' => 'manager'],
        ['name' => 'First Login Coordinator', 'email' => 'first-login-coordinator@example.com', 'role' => 'user'],
    ] as $identity) {
        $this->actingAs($admin)->post(route('admin.users.store'), [
            ...$identity,
            'is_active' => 1,
        ])->assertRedirect(route('admin.roles.index'));

        $user = User::where('email', $identity['email'])->firstOrFail();
        expect($user->must_change_password)->toBeTrue();

        $this->post(route('logout'));
        $this->post(route('login'), [
            'email' => $identity['email'],
            'password' => 'pass1234',
        ])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->get(route('admin.dashboard'))->assertRedirect(route('password.first-login.edit'));
        $this->get(route('password.first-login.edit'))
            ->assertOk()
            ->assertSee('Create your new password')
            ->assertSee('background-color: #0e7490 !important; color: #ffffff !important;', false);

        $this->put(route('password.first-login.update'), [
            'password' => 'pass1234',
            'password_confirmation' => 'pass1234',
        ])->assertSessionHasErrors('password');

        $this->put(route('password.first-login.update'), [
            'password' => 'a-new-secure-password',
            'password_confirmation' => 'a-new-secure-password',
        ])->assertRedirect(route('dashboard'));

        expect($user->fresh()->must_change_password)->toBeFalse()
            ->and(\Illuminate\Support\Facades\Hash::check('a-new-secure-password', $user->fresh()->password))->toBeTrue();
        $this->get(route('admin.dashboard'))->assertOk();
    }
});
