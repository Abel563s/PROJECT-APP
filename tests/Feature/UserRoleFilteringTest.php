<?php

use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('searches and filters identities by role and status', function () {
    $admin = User::factory()->create([
        'name' => 'Access Administrator',
        'email' => 'access-admin@example.com',
        'role' => 'admin',
    ]);
    $coordinator = User::factory()->create([
        'name' => 'Searchable Coordinator',
        'email' => 'coordinator@example.com',
        'employee_id' => 'EMP-100',
        'role' => 'user',
        'is_active' => true,
    ]);
    $inactiveCoordinator = User::factory()->create([
        'name' => 'Inactive Coordinator',
        'email' => 'inactive@example.com',
        'employee_id' => 'EMP-200',
        'role' => 'user',
        'is_active' => false,
    ]);
    $manager = User::factory()->create([
        'name' => 'Other Manager',
        'email' => 'manager@example.com',
        'role' => 'manager',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.roles.index', [
            'search' => 'EMP-100',
            'role' => 'user',
            'status' => 'active',
        ]))
        ->assertOk()
        ->assertSee('coordinator@example.com')
        ->assertDontSee('inactive@example.com')
        ->assertDontSee('manager@example.com')
        ->assertSee('name="search" value="EMP-100"', false)
        ->assertSee('value="user" selected', false)
        ->assertSee('value="active" selected', false);
});

it('sorts assignment projects alphabetically and adds search to create and edit selectors', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $coordinator = User::factory()->create(['role' => 'user']);
    Project::create(['project_name' => 'Alpha Project', 'project_code' => 'Z-200']);
    Project::create(['project_name' => 'Beta Project', 'project_code' => 'A-100']);

    $createResponse = $this->actingAs($admin)
        ->get(route('admin.users.create', ['partial' => 1]))
        ->assertOk()
        ->assertSee('id="createProjectSearch"', false)
        ->assertSee('data-project-search', false);
    expect(strpos($createResponse->getContent(), 'A-100'))
        ->toBeLessThan(strpos($createResponse->getContent(), 'Z-200'));

    $editResponse = $this->actingAs($admin)
        ->get(route('admin.users.edit', ['user' => $coordinator, 'partial' => 1]))
        ->assertOk()
        ->assertSee('id="editIdentityProjectSearch"', false)
        ->assertSee('data-project-search', false);
    expect(strpos($editResponse->getContent(), 'A-100'))
        ->toBeLessThan(strpos($editResponse->getContent(), 'Z-200'));
});
