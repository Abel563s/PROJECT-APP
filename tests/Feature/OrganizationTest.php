<?php

use App\Models\BusinessUnit;
use App\Models\Sector;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('superadmin can create a business unit', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);

    $this->actingAs($superadmin)
        ->postJson('/admin/business-units', [
            'name' => 'Engineering',
            'code' => 'ENG',
            'description' => 'Engineering Department',
            'is_active' => true,
        ])
        ->assertCreated()
        ->assertJson(['message' => 'Business Unit created successfully.']);

    $this->assertDatabaseHas('business_units', [
        'name' => 'Engineering',
        'code' => 'ENG',
    ]);
});

it('superadmin can update a business unit', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Original Name', 'code' => 'ORIG']);

    $this->actingAs($superadmin)
        ->putJson("/admin/business-units/{$bu->id}", [
            'name' => 'Updated Name',
            'code' => 'UPD',
            'description' => 'Updated description',
            'is_active' => true,
        ])
        ->assertOk();

    $this->assertDatabaseHas('business_units', [
        'id' => $bu->id,
        'name' => 'Updated Name',
        'code' => 'UPD',
    ]);
});

it('superadmin can create a sector under a business unit', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Engineering', 'code' => 'ENG']);

    $this->actingAs($superadmin)
        ->postJson('/admin/sectors', [
            'business_unit_id' => $bu->id,
            'name' => 'Software',
            'description' => 'Software Engineering',
            'is_active' => true,
        ])
        ->assertCreated();

    $this->assertDatabaseHas('sectors', [
        'business_unit_id' => $bu->id,
        'name' => 'Software',
    ]);
});

it('superadmin can update a sector', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Engineering', 'code' => 'ENG']);
    $sector = Sector::create(['business_unit_id' => $bu->id, 'name' => 'Original Sector']);

    $this->actingAs($superadmin)
        ->putJson("/admin/sectors/{$sector->id}", [
            'business_unit_id' => $bu->id,
            'name' => 'Updated Sector',
            'description' => 'Updated description',
            'is_active' => true,
        ])
        ->assertOk();

    $this->assertDatabaseHas('sectors', [
        'id' => $sector->id,
        'name' => 'Updated Sector',
    ]);
});

it('superadmin can view single business unit via api', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Engineering', 'code' => 'ENG']);

    $this->actingAs($superadmin)
        ->getJson("/admin/business-units/{$bu->id}")
        ->assertOk()
        ->assertJson(['name' => 'Engineering']);
});

it('superadmin can view single sector via api', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Engineering', 'code' => 'ENG']);
    $sector = Sector::create(['business_unit_id' => $bu->id, 'name' => 'Software']);

    $this->actingAs($superadmin)
        ->getJson("/admin/sectors/{$sector->id}")
        ->assertOk()
        ->assertJson(['name' => 'Software']);
});

it('superadmin can list all business units via api', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    BusinessUnit::create(['name' => 'BU 1', 'code' => 'B1']);
    BusinessUnit::create(['name' => 'BU 2', 'code' => 'B2']);

    $this->actingAs($superadmin)
        ->getJson('/admin/business-units')
        ->assertOk()
        ->assertJsonStructure(['business_units'])
        ->assertJsonCount(2, 'business_units');
});

it('superadmin can delete a business unit without users or sectors', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Empty BU', 'code' => 'EMPTY']);

    $this->actingAs($superadmin)
        ->deleteJson("/admin/business-units/{$bu->id}")
        ->assertOk();

    $this->assertDatabaseMissing('business_units', ['id' => $bu->id]);
});

it('superadmin can deactivate a business unit with users', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'BU With Users', 'code' => 'BWR']);
    $user = User::factory()->create(['business_unit_id' => $bu->id, 'role' => 'user']);

    $this->actingAs($superadmin)
        ->deleteJson("/admin/business-units/{$bu->id}")
        ->assertOk()
        ->assertJson(['deactivated' => true]);

    $this->assertDatabaseHas('business_units', [
        'id' => $bu->id,
        'is_active' => false,
    ]);
});

it('superadmin can delete a sector without users', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Eng', 'code' => 'ENG']);
    $sector = Sector::create(['business_unit_id' => $bu->id, 'name' => 'Sector to Delete']);

    $this->actingAs($superadmin)
        ->deleteJson("/admin/sectors/{$sector->id}")
        ->assertOk();

    $this->assertDatabaseMissing('sectors', ['id' => $sector->id]);
});

it('superadmin can deactivate a sector with users', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Eng', 'code' => 'ENG']);
    $sector = Sector::create(['business_unit_id' => $bu->id, 'name' => 'Sector With Users']);
    User::factory()->create(['sector_id' => $sector->id, 'role' => 'user', 'must_change_password' => false]);

    $this->actingAs($superadmin)
        ->deleteJson("/admin/sectors/{$sector->id}")
        ->assertOk()
        ->assertJson(['deactivated' => true]);

    $this->assertDatabaseHas('sectors', [
        'id' => $sector->id,
        'is_active' => false,
    ]);
});

it('admin cannot grant superadmin role', function () {
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false]);

    $this->actingAs($admin)
        ->postJson(route('admin.users.store'), [
            'name' => 'New Superadmin',
            'email' => 'new-superadmin@example.com',
            'role' => 'superadmin',
            'is_active' => 1,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('role');
});

it('superadmin can create a superadmin identity', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);

    $this->actingAs($superadmin)
        ->postJson(route('admin.users.store'), [
            'name' => 'New Superadmin',
            'email' => 'new-superadmin@example.com',
            'role' => 'superadmin',
            'is_active' => 1,
        ])
        ->assertCreated();

    $this->assertDatabaseHas('users', [
        'email' => 'new-superadmin@example.com',
        'role' => 'superadmin',
    ]);
});

it('admin cannot access business unit api endpoints', function () {
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false]);

    $this->actingAs($admin)
        ->getJson('/admin/business-units')
        ->assertForbidden();

    $this->actingAs($admin)
        ->postJson('/admin/business-units', ['name' => 'Test'])
        ->assertForbidden();
});

it('management cannot access business unit api endpoints', function () {
    $manager = User::factory()->create(['role' => 'manager', 'must_change_password' => false]);

    $this->actingAs($manager)
        ->getJson('/admin/business-units')
        ->assertForbidden();
});

it('unauthenticated user cannot access business unit endpoints', function () {
    $this->getJson('/admin/business-units')->assertUnauthorized();
    $this->postJson('/admin/business-units', ['name' => 'Test'])->assertUnauthorized();
});

it('admin sees their own business unit in create modal', function () {
    $bu = BusinessUnit::create(['name' => 'Admin BU', 'code' => 'ADM']);
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false, 'business_unit_id' => $bu->id]);

    $this->actingAs($admin)
        ->get(route('admin.users.create', ['partial' => 1]))
        ->assertOk()
        ->assertSee('Admin BU');
});

it('superadmin sees all business units in create modal', function () {
    $bu1 = BusinessUnit::create(['name' => 'BU 1', 'code' => 'B1']);
    $bu2 = BusinessUnit::create(['name' => 'BU 2', 'code' => 'B2']);
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);

    $this->actingAs($superadmin)
        ->get(route('admin.users.create', ['partial' => 1]))
        ->assertOk()
        ->assertSee('BU 1')
        ->assertSee('BU 2');
});

it('superadmin role option only appears for superadmin in create modal', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false]);

    $this->actingAs($superadmin)
        ->get(route('admin.users.create', ['partial' => 1]))
        ->assertOk()
        ->assertSee('value="superadmin">Superadmin</option>', false);

    $this->actingAs($admin)
        ->get(route('admin.users.create', ['partial' => 1]))
        ->assertOk()
        ->assertDontSee('value="superadmin"', false);
});

it('superadmin role option only appears for superadmin in edit modal', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false]);
    $user = User::factory()->create(['role' => 'user', 'must_change_password' => false]);

    $this->actingAs($superadmin)
        ->get(route('admin.users.edit', ['user' => $user, 'partial' => 1]))
        ->assertOk()
        ->assertSee('value="superadmin"', false);

    $this->actingAs($admin)
        ->get(route('admin.users.edit', ['user' => $user, 'partial' => 1]))
        ->assertOk()
        ->assertDontSee('value="superadmin"', false);
});

it('superadmin can assign business unit and sector when creating user', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Eng', 'code' => 'ENG']);
    $sector = Sector::create(['business_unit_id' => $bu->id, 'name' => 'Software']);

    $this->actingAs($superadmin)
        ->postJson(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => 'test-bu-sector@example.com',
            'role' => 'manager',
            'is_active' => 1,
            'business_unit_id' => $bu->id,
            'sector_id' => $sector->id,
        ])
        ->assertCreated();

    $this->assertDatabaseHas('users', [
        'email' => 'test-bu-sector@example.com',
        'business_unit_id' => $bu->id,
        'sector_id' => $sector->id,
    ]);
});

it('admin can only create users within their own business unit', function () {
    $bu = BusinessUnit::create(['name' => 'Admin BU', 'code' => 'ADM']);
    $otherBu = BusinessUnit::create(['name' => 'Other BU', 'code' => 'OTH']);
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false, 'business_unit_id' => $bu->id]);

    $this->actingAs($admin)
        ->postJson(route('admin.users.store'), [
            'name' => 'Management User',
            'email' => 'management@example.com',
            'role' => 'manager',
            'is_active' => 1,
            'business_unit_id' => $otherBu->id,
        ])
        ->assertCreated();

    $user = User::where('email', 'management@example.com')->firstOrFail();
    expect($user->business_unit_id)->toBe($bu->id)
        ->and($user->business_unit_id)->not->toBe($otherBu->id);
});

it('admin cannot assign sector from a different business unit', function () {
    $adminBu = BusinessUnit::create(['name' => 'Admin BU', 'code' => 'ADM']);
    $otherBu = BusinessUnit::create(['name' => 'Other BU', 'code' => 'OTH']);
    $otherSector = Sector::create(['business_unit_id' => $otherBu->id, 'name' => 'Other Sector']);
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false, 'business_unit_id' => $adminBu->id]);

    $this->actingAs($admin)
        ->postJson(route('admin.users.store'), [
            'name' => 'Management User',
            'email' => 'cross-bu-sector@example.com',
            'role' => 'manager',
            'is_active' => 1,
            'business_unit_id' => $adminBu->id,
            'sector_id' => $otherSector->id,
        ])
        ->assertCreated();

    $user = User::where('email', 'cross-bu-sector@example.com')->firstOrFail();
    expect($user->sector_id)->toBeNull();
});

it('superadmin can assign any sector from any business unit', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Eng', 'code' => 'ENG']);
    $sector = Sector::create(['business_unit_id' => $bu->id, 'name' => 'Software']);

    $this->actingAs($superadmin)
        ->postJson(route('admin.users.store'), [
            'name' => 'Cross BU User',
            'email' => 'cross-bu@example.com',
            'role' => 'manager',
            'is_active' => 1,
            'business_unit_id' => $bu->id,
            'sector_id' => $sector->id,
        ])
        ->assertCreated();

    $this->assertDatabaseHas('users', [
        'email' => 'cross-bu@example.com',
        'sector_id' => $sector->id,
    ]);
});

it('admin cannot access superadmin-only routes with role middleware', function () {
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false]);

    $this->actingAs($admin)
        ->get('/admin/business-units')
        ->assertForbidden();
});

it('management cannot access superadmin-only routes', function () {
    $manager = User::factory()->create(['role' => 'manager', 'must_change_password' => false]);

    $this->actingAs($manager)
        ->get('/admin/business-units')
        ->assertForbidden();
});

it('roles page shows business unit column for superadmin', function () {
    $bu = BusinessUnit::create(['name' => 'Test BU', 'code' => 'TST']);
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false, 'business_unit_id' => $bu->id]);

    $this->actingAs($superadmin)
        ->get(route('admin.roles.index'))
        ->assertOk()
        ->assertSee('Business Unit')
        ->assertSee('Sector');
});

it('dropdown data returns business units and sectors', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Test BU', 'code' => 'TST']);
    $sector = Sector::create(['business_unit_id' => $bu->id, 'name' => 'Test Sector']);

    $this->actingAs($superadmin)
        ->getJson('/admin/organization/dropdown-data')
        ->assertOk()
        ->assertJsonStructure(['business_units', 'sectors']);
});

it('admin dropdown data returns only admin\'s business unit', function () {
    $bu = BusinessUnit::create(['name' => 'Admin BU', 'code' => 'ADM']);
    $otherBu = BusinessUnit::create(['name' => 'Other BU', 'code' => 'OTH']);
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false, 'business_unit_id' => $bu->id]);

    $this->actingAs($admin)
        ->getJson('/admin/organization/admin-dropdown-data')
        ->assertOk()
        ->assertJsonStructure(['business_units'])
        ->assertJsonCount(1, 'business_units');
});

it('prevent duplicate sector names within same business unit', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Eng', 'code' => 'ENG']);
    Sector::create(['business_unit_id' => $bu->id, 'name' => 'Duplicate']);

    $this->actingAs($superadmin)
        ->postJson('/admin/sectors', [
            'business_unit_id' => $bu->id,
            'name' => 'Duplicate',
            'is_active' => true,
        ])
        ->assertStatus(422);
});

it('allow duplicate sector names across different business units', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu1 = BusinessUnit::create(['name' => 'BU 1', 'code' => 'B1']);
    $bu2 = BusinessUnit::create(['name' => 'BU 2', 'code' => 'B2']);
    Sector::create(['business_unit_id' => $bu1->id, 'name' => 'Shared Name']);

    $this->actingAs($superadmin)
        ->postJson('/admin/sectors', [
            'business_unit_id' => $bu2->id,
            'name' => 'Shared Name',
            'is_active' => true,
        ])
        ->assertCreated();

    $this->assertDatabaseHas('sectors', [
        'business_unit_id' => $bu2->id,
        'name' => 'Shared Name',
    ]);
});

it('superadmin can toggle business unit status', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Test', 'code' => 'T1', 'is_active' => true]);

    $this->actingAs($superadmin)
        ->patchJson("/admin/business-units/{$bu->id}/toggle-status")
        ->assertOk();

    $this->assertDatabaseHas('business_units', ['id' => $bu->id, 'is_active' => false]);
});

it('superadmin can toggle sector status', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $bu = BusinessUnit::create(['name' => 'Test', 'code' => 'T1']);
    $sector = Sector::create(['business_unit_id' => $bu->id, 'name' => 'Test Sector', 'is_active' => true]);

    $this->actingAs($superadmin)
        ->patchJson("/admin/sectors/{$sector->id}/toggle-status")
        ->assertOk();

    $this->assertDatabaseHas('sectors', ['id' => $sector->id, 'is_active' => false]);
});

it('admin cannot access organization dropdown data', function () {
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false]);

    $this->actingAs($admin)
        ->getJson('/admin/organization/dropdown-data')
        ->assertForbidden();
});

it('admin can access admin dropdown data', function () {
    $bu = BusinessUnit::create(['name' => 'Admin BU', 'code' => 'ADM']);
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false, 'business_unit_id' => $bu->id]);

    $this->actingAs($admin)
        ->getJson('/admin/organization/admin-dropdown-data')
        ->assertOk()
        ->assertJsonStructure(['business_units']);
});

it('non superadmin or admin cannot access admin dropdown data', function () {
    $manager = User::factory()->create(['role' => 'manager', 'must_change_password' => false]);

    $this->actingAs($manager)
        ->getJson('/admin/organization/admin-dropdown-data')
        ->assertForbidden();
});

it('users page displays superadmin in role count', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);

    $this->actingAs($superadmin)
        ->get(route('admin.roles.index'))
        ->assertOk()
        ->assertSee('superadmin');
});

it('superadmin filter works in users page', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    $otherSuper = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    User::factory()->create(['role' => 'admin', 'must_change_password' => false]);
    User::factory()->create(['role' => 'manager']);

     $this->actingAs($superadmin)
        ->get(route('admin.roles.index', ['role' => 'superadmin']))
        ->assertOk();
});

it('superadmin can load business unit manage html partial', function () {
    $superadmin = User::factory()->create(['role' => 'superadmin', 'must_change_password' => false]);
    BusinessUnit::create(['name' => 'Engineering', 'code' => 'ENG', 'description' => 'Dev team']);

    $this->actingAs($superadmin)
        ->get('/admin/business-units/manage?partial=1')
        ->assertOk()
        ->assertSee('Business Units')
        ->assertSee('Engineering')
        ->assertSee('buFormContainer')
        ->assertSee('orgManagementErrors');
});

it('non-superadmin cannot load business unit manage html', function () {
    $admin = User::factory()->create(['role' => 'admin', 'must_change_password' => false]);

    $this->actingAs($admin)
        ->get('/admin/business-units/manage?partial=1')
        ->assertForbidden();
});
