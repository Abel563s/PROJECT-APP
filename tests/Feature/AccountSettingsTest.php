<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('renders profile and password controls in one account settings form', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $this->actingAs($user)
        ->get(route('admin.settings.index'))
        ->assertOk()
        ->assertSee('Account settings')
        ->assertSee('Personal details')
        ->assertSee('Change password')
        ->assertSee('Save account settings')
        ->assertSee('name="current_password"', false)
        ->assertSee('name="password_confirmation"', false);
});

it('updates profile and password together when new credentials are provided', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('current-password'),
    ]);

    $this->actingAs($user)
        ->patch(route('admin.settings.account.update'), [
            'name' => 'Updated Account Name',
            'email' => 'updated-account@example.com',
            'current_password' => 'current-password',
            'password' => 'replacement-password',
            'password_confirmation' => 'replacement-password',
        ])
        ->assertRedirect(route('admin.settings.index'))
        ->assertSessionHas('success');

    expect($user->fresh()->name)->toBe('Updated Account Name')
        ->and($user->fresh()->email)->toBe('updated-account@example.com')
        ->and(Hash::check('replacement-password', $user->fresh()->password))->toBeTrue();
});

it('allows profile changes without changing the password', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('current-password'),
    ]);
    $originalPassword = $user->password;

    $this->actingAs($user)
        ->patch(route('admin.settings.account.update'), [
            'name' => 'Profile Only Update',
            'email' => 'profile-only@example.com',
        ])
        ->assertRedirect(route('admin.settings.index'));

    expect($user->fresh()->name)->toBe('Profile Only Update')
        ->and($user->fresh()->password)->toBe($originalPassword);
});

it('rejects an incorrect current password without applying profile changes', function () {
    $user = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('current-password'),
    ]);

    $this->actingAs($user)
        ->from(route('admin.settings.index'))
        ->patch(route('admin.settings.account.update'), [
            'name' => 'Must Not Be Saved',
            'email' => $user->email,
            'current_password' => 'incorrect-password',
            'password' => 'replacement-password',
            'password_confirmation' => 'replacement-password',
        ])
        ->assertRedirect(route('admin.settings.index'))
        ->assertSessionHasErrors('current_password');

    expect($user->fresh()->name)->not->toBe('Must Not Be Saved');
});
