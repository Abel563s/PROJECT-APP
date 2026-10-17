<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the saved-theme bootstrap and accessible toggle on the login page', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('projects-app-theme', false)
        ->assertSee('data-theme-toggle', false)
        ->assertSee('aria-pressed="false"', false)
        ->assertSee('Switch to dark mode', false)
        ->assertSee('data-theme-label class="whitespace-nowrap text-xs font-semibold">Light mode', false);
});

it('renders the shared theme toggle in the authenticated application shell', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('data-theme-toggle', false)
        ->assertSee('data-theme-label class="whitespace-nowrap text-xs font-semibold">Light mode', false)
        ->assertSee('projects-app-theme', false);
});
