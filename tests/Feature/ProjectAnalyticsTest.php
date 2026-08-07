<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('loads the analytics page without relying on an external chart script', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'is_active' => true,
    ]);

    $this->actingAs($admin);

    $response = $this->get(route('admin.projects.analytics'));

    $response->assertOk();
    $response->assertSee('Performance S-Curve Analytics');
    $response->assertSee('No CPI data available.');
    $response->assertDontSee('https://cdn.jsdelivr.net/npm/chart.js');
});
