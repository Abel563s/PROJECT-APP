<?php

use App\Models\ProjectRiskPool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
    \App\Models\RiskCategory::query()->update(['risk_type' => 'Threat']);
});

function adminUser(): User
{
    return User::factory()->create(['role' => 'superadmin']);
}

function managerUser(): User
{
    return User::factory()->create(['role' => 'manager']);
}

function coordinatorUser(): User
{
    return User::factory()->create(['role' => 'user']);
}

function validRiskData(array $overrides = []): array
{
    return array_merge([
        'risk_id' => '1.01',
        'risk_type' => 'Threat',
        'risk_category' => 'Financial Risks',
        'specific_risk' => 'Advance Payment Delay (Client)',
        'detailed_risk_explanation' => 'A delay in receiving the client advance payment.',
        'impact_on_timeline' => 'High',
        'impact_on_cost' => 'Medium',
        'overall_impact_level' => 'High',
        'stakeholder' => 'Client, Project Manager, Senior Management',
        'recommended_mitigation_measures' => 'Negotiate milestone-based releases.',
    ], $overrides);
}

it('renders the empty risk pool page for an admin', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.index'))
        ->assertOk()
        ->assertViewHas('stats', fn ($stats) => $stats['total'] === 0)
        ->assertViewHas('isAdmin', true)
        ->assertSee('Risk Pool')
        ->assertSee('Add New Risk')
        ->assertSee('Risk ID')
        ->assertSee('No Risks in Pool');
});

it('renders the risk pool page with seeded risks and summary counts', function () {
    Artisan::call('db:seed', ['--class' => 'RiskPoolSeeder']);
    $admin = adminUser();

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.index'))
        ->assertOk()
        ->assertViewHas('stats', fn ($stats) => $stats['total'] === 10)
        ->assertSee('Advance Payment Delay (Client)')
        ->assertSee('Workplace Accidents')
        ->assertSee('Inaccurate Project duration');
});

it('seeds the risk pool idempotently', function () {
    Artisan::call('db:seed', ['--class' => 'RiskPoolSeeder']);
    Artisan::call('db:seed', ['--class' => 'RiskPoolSeeder']);

    expect(ProjectRiskPool::count())->toBe(10)
        ->and(ProjectRiskPool::where('risk_id', '1.01')->exists())->toBeTrue();
});

it('renders the spreadsheet editor for an admin creating risks', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.create-modal'))
        ->assertOk()
        ->assertSee('Save All')
        ->assertSee('Add Row')
        ->assertSee('Manage Categories')
        ->assertSee('Risk Type')
        ->assertSee('Risk Category')
        ->assertSee('Specific Risk');
});

it('creates a risk via ajax as an admin', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->postJson(route('admin.projects.risk-pool.store'), validRiskData())
        ->assertCreated()
        ->assertJson([
            'message' => 'Risk registered successfully.',
            'refresh' => true,
        ]);

    $risk = ProjectRiskPool::firstOrFail();
    expect($risk->risk_id)->toBe('1.01')
        ->and($risk->risk_type)->toBe('Threat')
        ->and($risk->specific_risk)->toBe('Advance Payment Delay (Client)')
        ->and($risk->created_by)->toBe($admin->id);
});

it('validates and prevents duplicate risk ids', function () {
    $admin = adminUser();
    $admin->forceFill(['must_change_password' => false])->save();

    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01']));

    $this->actingAs($admin)
        ->postJson(route('admin.projects.risk-pool.store'), validRiskData())
        ->assertStatus(422)
        ->assertJsonValidationErrors('risk_id');
});

it('updates an existing risk via ajax as an admin', function () {
    $admin = adminUser();
    $risk = ProjectRiskPool::create(validRiskData());
    \App\Models\RiskCategory::where('name', 'Financial Risks')->update(['risk_type' => 'Opportunity']);

    $this->actingAs($admin)
        ->putJson(route('admin.projects.risk-pool.update', $risk), validRiskData([
            'specific_risk' => 'Updated Risk Title',
            'risk_type' => 'Opportunity',
            'overall_impact_level' => 'Medium',
        ]))
        ->assertOk()
        ->assertJson(['message' => 'Risk updated successfully.', 'refresh' => true]);

    $risk->refresh();
    expect($risk->specific_risk)->toBe('Updated Risk Title')
        ->and($risk->risk_type)->toBe('Opportunity')
        ->and($risk->overall_impact_level)->toBe('Medium');
});

it('renders the spreadsheet editor for an admin editing a risk', function () {
    $admin = adminUser();
    $risk = ProjectRiskPool::create(validRiskData());

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.edit-modal', $risk))
        ->assertOk()
        ->assertSee('Save All')
        ->assertSee('Risk Type')
        ->assertSee('specific_risk')
        ->assertSee('Advance Payment Delay (Client)');
});

it('renders the view modal content for a risk', function () {
    $admin = adminUser();
    $risk = ProjectRiskPool::create(validRiskData());

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.show', [$risk, 'partial' => 1]))
        ->assertOk()
        ->assertSee('Detailed Risk Explanation')
        ->assertSee('A delay in receiving the client advance payment.')
        ->assertSee('Stakeholder')
        ->assertSee('Threat')
        ->assertSee('1.01');
});

it('suggests the next risk id by category', function () {
    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01']));
    $admin = adminUser();

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.suggest-id', ['category' => 'Financial Risks', 'risk_type' => 'Threat']))
        ->assertOk()
        ->assertJson(['risk_id' => '1.02']);
});

it('returns the first risk id when the pool is empty for a category', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.suggest-id', ['category' => 'Safety Risks', 'risk_type' => 'Threat']))
        ->assertOk()
        ->assertJson(['risk_id' => '5.01']);
});

it('does not suggest a fallback prefix for categories without a numeric code', function () {
    $admin = adminUser();
    \App\Models\RiskCategory::create([
        'name' => 'Uncoded Risks',
        'risk_type' => 'Threat',
        'is_active' => true,
    ]);

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.suggest-id', [
            'category' => 'Uncoded Risks',
            'risk_type' => 'Threat',
        ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('category');
});

it('rejects risk id suggestions for a category assigned to another risk type', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.suggest-id', [
            'category' => 'Financial Risks',
            'risk_type' => 'Opportunity',
        ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors('category');
});

it('rejects saving a risk under a category assigned to another risk type', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->postJson(route('admin.projects.risk-pool.bulk-store'), [
            'rows' => [
                validRiskData(['risk_type' => 'Opportunity']),
            ],
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('rows.0.risk_category');
});

it('returns partial summary and table as json', function () {
    $admin = adminUser();
    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01', 'overall_impact_level' => 'High']));
    ProjectRiskPool::create(validRiskData(['risk_id' => '2.01', 'risk_category' => 'Procurement Risks', 'overall_impact_level' => 'Medium']));
    ProjectRiskPool::create(validRiskData(['risk_id' => '3.01', 'risk_category' => 'Labor Risks', 'overall_impact_level' => 'Low']));

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.index', ['partial' => 'summary']))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonStructure(['html']);

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.index', ['partial' => 'table']))
        ->assertOk()
        ->assertJsonStructure(['html']);
});

it('filters risk pool results by time cost and quality impact', function () {
    $admin = adminUser();
    ProjectRiskPool::create(validRiskData([
        'risk_id' => '1.01',
        'specific_risk' => 'Matching impacts',
        'impact_on_timeline' => 'High',
        'impact_on_cost' => 'Low',
        'overall_impact_level' => 'Medium',
    ]));
    ProjectRiskPool::create(validRiskData([
        'risk_id' => '1.02',
        'specific_risk' => 'Different impacts',
        'impact_on_timeline' => 'Low',
        'impact_on_cost' => 'Low',
        'overall_impact_level' => 'Medium',
    ]));

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.index', [
            'impact_on_timeline' => 'High',
            'impact_on_cost' => 'Low',
            'overall_impact_level' => 'Medium',
        ]))
        ->assertOk()
        ->assertSee('Matching impacts')
        ->assertDontSee('Different impacts');
});

it('filters risks by category and impact level', function () {
    $admin = adminUser();
    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01', 'risk_category' => 'Financial Risks', 'overall_impact_level' => 'High']));
    ProjectRiskPool::create(validRiskData(['risk_id' => '3.01', 'risk_category' => 'Labor Risks', 'overall_impact_level' => 'Low', 'specific_risk' => 'Labor Shortages']));

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.index', ['partial' => 'table', 'risk_category' => 'Labor Risks']))
        ->assertOk()
        ->assertSee('Labor Shortages', false)
        ->assertDontSee('Advance Payment Delay (Client)', false);

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.index', ['overall_impact_level' => 'Low']))
        ->assertOk()
        ->assertViewHas('risks', fn ($risks) => $risks->count() === 1);
});

it('searches risks by keyword', function () {
    $admin = adminUser();
    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01', 'specific_risk' => 'Advance Payment Delay (Client)']));
    ProjectRiskPool::create(validRiskData([
        'risk_id' => '3.01',
        'risk_category' => 'Labor Risks',
        'specific_risk' => 'Labor Shortages',
        'detailed_risk_explanation' => 'Insufficient skilled labor endangering project delivery schedules.',
    ]));

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.index', ['search' => 'Payment']))
        ->assertOk()
        ->assertViewHas('risks', fn ($risks) => $risks->count() === 1)
        ->assertViewHas('risks', fn ($risks) => $risks->first()->specific_risk === 'Advance Payment Delay (Client)');
});

it('does not allow the add new risk button for non-admins', function () {
    $manager = managerUser();
    $coordinator = coordinatorUser();

    $this->actingAs($manager)
        ->get(route('admin.projects.risk-pool.index'))
        ->assertOk()
        ->assertDontSee('Add New Risk</span>');

    $this->actingAs($coordinator)
        ->get(route('admin.projects.risk-pool.index'))
        ->assertOk()
        ->assertDontSee('Add New Risk</span>');
});

it('allows managers and coordinators to view the risk pool read-only', function () {
    $risk = ProjectRiskPool::create(validRiskData());

    $manager = managerUser();
    $coordinator = coordinatorUser();

    // Index is viewable.
    $this->actingAs($manager)->get(route('admin.projects.risk-pool.index'))->assertOk();
    $this->actingAs($coordinator)->get(route('admin.projects.risk-pool.index'))->assertOk();

    // View modal is viewable.
    $this->actingAs($manager)
        ->get(route('admin.projects.risk-pool.show', [$risk, 'partial' => 1]))
        ->assertOk()
        ->assertSee('Detailed Risk Explanation');

    $this->actingAs($coordinator)
        ->get(route('admin.projects.risk-pool.show', [$risk, 'partial' => 1]))
        ->assertOk();
});

it('blocks non-admins from write operations on the risk pool', function () {
    $manager = managerUser();
    $coordinator = coordinatorUser();
    $risk = ProjectRiskPool::create(validRiskData());

    foreach ([$manager, $coordinator] as $user) {
        // Create modal (GET) -> blocked by role middleware.
        $this->actingAs($user)
            ->get(route('admin.projects.risk-pool.create-modal'))
            ->assertForbidden();

        // Edit modal (GET) -> blocked by role middleware.
        $this->actingAs($user)
            ->get(route('admin.projects.risk-pool.edit-modal', $risk))
            ->assertForbidden();

        // Store (non-GET) -> blocked by role middleware.
        $this->actingAs($user)
            ->postJson(route('admin.projects.risk-pool.store'), validRiskData(['risk_id' => '1.03']))
            ->assertForbidden();

        // Update (non-GET) -> blocked, and the risk is not modified.
        $this->actingAs($user)
            ->putJson(route('admin.projects.risk-pool.update', $risk), validRiskData([
                'specific_risk' => 'Should Not Change',
            ]))
            ->assertForbidden();

        $risk->refresh();
        expect($risk->specific_risk)->toBe('Advance Payment Delay (Client)');
    }
});

it('returns the cards partial as json', function () {
    $admin = adminUser();
    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01']));

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.index', ['partial' => 'cards']))
        ->assertOk()
        ->assertJsonStructure(['html'])
        ->assertSee('Advance Payment Delay (Client)', false)
        ->assertSee('1.01', false);
});

it('suggests the next risk id for a dynamically added category', function () {
    \App\Models\RiskCategory::create(['name' => 'Custom Risk', 'code' => '99', 'risk_type' => 'Threat', 'display_order' => 99, 'is_active' => true]);
    $admin = adminUser();

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.suggest-id', ['category' => 'Custom Risk', 'risk_type' => 'Threat']))
        ->assertOk()
        ->assertJson(['risk_id' => '99.01']);
});

it('bulk creates multiple risks in one operation', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->postJson(route('admin.projects.risk-pool.bulk-store'), [
            'rows' => [
                validRiskData(['risk_id' => '1.01']),
                validRiskData(['risk_id' => '2.01', 'risk_category' => 'Procurement Risks', 'specific_risk' => 'Late Supplier Delivery']),
                validRiskData(['risk_id' => '3.01', 'risk_category' => 'Labor Risks', 'specific_risk' => 'Labor Shortages']),
            ],
        ])
        ->assertOk()
        ->assertJson(['created' => 3, 'updated' => 0, 'refresh' => true]);

    expect(ProjectRiskPool::whereIn('risk_id', ['1.01', '2.01', '3.01'])->count())->toBe(3)
        ->and(ProjectRiskPool::where('specific_risk', 'Late Supplier Delivery')->exists())->toBeTrue();
});

it('bulk store rejects duplicate risk ids within the batch', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->postJson(route('admin.projects.risk-pool.bulk-store'), [
            'rows' => [
                validRiskData(['risk_id' => '1.01']),
                validRiskData(['risk_id' => '1.01', 'specific_risk' => 'Same ID']),
            ],
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('rows.1.risk_id');

    expect(ProjectRiskPool::count())->toBe(0);
});

it('bulk store rejects risk ids that already exist', function () {
    $admin = adminUser();
    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01']));

    $this->actingAs($admin)
        ->postJson(route('admin.projects.risk-pool.bulk-store'), [
            'rows' => [
                validRiskData(['risk_id' => '1.01', 'specific_risk' => 'Clash']),
            ],
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors('rows.0.risk_id');

    expect(ProjectRiskPool::where('specific_risk', 'Clash')->exists())->toBeFalse();
});

it('bulk store updates existing records and skips unchanged ones', function () {
    $admin = adminUser();
    $existing = ProjectRiskPool::create(validRiskData(['risk_id' => '1.01', 'overall_impact_level' => 'High']));

    $this->actingAs($admin)
        ->postJson(route('admin.projects.risk-pool.bulk-store'), [
            'rows' => [
                [
                    'id' => $existing->id,
                    'risk_id' => '1.01',
                    'risk_type' => 'Threat',
                    'risk_category' => 'Financial Risks',
                    'specific_risk' => 'Updated Title',
                    'overall_impact_level' => 'Medium',
                ],
            ],
        ])
        ->assertOk()
        ->assertJson(['created' => 0, 'updated' => 1]);

    $existing->refresh();
    expect($existing->specific_risk)->toBe('Updated Title')
        ->and($existing->overall_impact_level)->toBe('Medium');
});

it('blocks non-admins from the bulk store endpoint', function () {
    $manager = managerUser();
    $coordinator = coordinatorUser();

    foreach ([$manager, $coordinator] as $user) {
        $this->actingAs($user)
            ->postJson(route('admin.projects.risk-pool.bulk-store'), ['rows' => [validRiskData(['risk_id' => '1.01'])]])
            ->assertForbidden();
    }
    expect(ProjectRiskPool::count())->toBe(0);
});

it('lets an admin manage risk categories in the database', function () {
    $admin = adminUser();

    $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.categories.index'))
        ->assertOk()
        ->assertSee('Risk Categories')
        ->assertSee('Financial Risks');

    $this->actingAs($admin)
        ->postJson(route('admin.projects.risk-pool.categories.store'), [
            'name' => 'Technology Risks', 'code' => '12', 'risk_type' => 'Opportunity', 'display_order' => 12, 'is_active' => true,
        ])
        ->assertCreated()
        ->assertJson(['refresh' => true]);

    expect(\App\Models\RiskCategory::where('name', 'Technology Risks')->exists())->toBeTrue();
    expect(\App\Models\RiskCategory::where('name', 'Technology Risks')->value('risk_type'))->toBe('Opportunity');

    $cat = \App\Models\RiskCategory::where('name', 'Financial Risks')->first();
    $this->actingAs($admin)
        ->putJson(route('admin.projects.risk-pool.categories.update', $cat), [
            'name' => 'Financial Risks', 'code' => '1', 'risk_type' => 'Threat', 'display_order' => 1, 'is_active' => true,
        ])
        ->assertOk();
});

it('filters risk categories by assigned risk type and synchronizes used risks when reassigned', function () {
    $admin = adminUser();
    $risk = ProjectRiskPool::create(validRiskData(['risk_id' => '1.01']));
    $category = \App\Models\RiskCategory::where('name', 'Financial Risks')->firstOrFail();

    $this->actingAs($admin)
        ->putJson(route('admin.projects.risk-pool.categories.update', $category), [
            'risk_type' => 'Opportunity',
        ])
        ->assertOk();

    expect($risk->fresh()->risk_type)->toBe('Opportunity');

    $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.categories.index', ['format' => 'options']))
        ->assertOk()
        ->assertJsonFragment(['name' => 'Financial Risks', 'risk_type' => 'Opportunity', 'code' => '1']);
});

it('partial-updates a category with only is_active (toggle without name)', function () {
    $admin = adminUser();
    $cat = \App\Models\RiskCategory::where('name', 'Financial Risks')->first();

    // Deactivating must not require the name field.
    $this->actingAs($admin)
        ->putJson(route('admin.projects.risk-pool.categories.update', $cat), [
            'is_active' => false,
        ])
        ->assertOk();

    expect(\App\Models\RiskCategory::where('name', 'Financial Risks')->value('is_active'))->toBeFalse();

    // Re-activating must also work without the name field.
    $this->actingAs($admin)
        ->putJson(route('admin.projects.risk-pool.categories.update', $cat), [
            'is_active' => true,
        ])
        ->assertOk();

    expect(\App\Models\RiskCategory::where('name', 'Financial Risks')->value('is_active'))->toBeTrue();
});

it('partial-updates a category with only code (inline edit without name)', function () {
    $admin = adminUser();
    $cat = \App\Models\RiskCategory::where('name', 'Financial Risks')->first();

    $this->actingAs($admin)
        ->putJson(route('admin.projects.risk-pool.categories.update', $cat), [
            'code' => '99',
        ])
        ->assertOk();

    expect(\App\Models\RiskCategory::where('name', 'Financial Risks')->value('code'))->toBe('99');
    // is_active must be preserved (not flipped to false).
    expect(\App\Models\RiskCategory::where('name', 'Financial Risks')->value('is_active'))->toBeTrue();
});

it('prevents deactivating-in-use categories from being deleted and blocks non-admins', function () {
    $manager = managerUser();
    $coordinator = coordinatorUser();

    foreach ([$manager, $coordinator] as $user) {
        $this->actingAs($user)
            ->get(route('admin.projects.risk-pool.categories.index'))
            ->assertForbidden();
    }

    $admin = adminUser();
    ProjectRiskPool::create(validRiskData(['risk_category' => 'Financial Risks']));
    $cat = \App\Models\RiskCategory::where('name', 'Financial Risks')->first();

    $this->actingAs($admin)
        ->deleteJson(route('admin.projects.risk-pool.categories.destroy', $cat))
        ->assertStatus(422);

    // Renaming a category cascades to its pooled records.
    $this->actingAs($admin)
        ->putJson(route('admin.projects.risk-pool.categories.update', $cat), [
            'name' => 'Financial & Budget Risks', 'code' => '1', 'display_order' => 1, 'is_active' => true,
        ])
        ->assertOk();

    expect(ProjectRiskPool::where('risk_category', 'Financial & Budget Risks')->exists())->toBeTrue();
});

it('allows an admin to delete an unused category', function () {
    $admin = adminUser();
    $cat = \App\Models\RiskCategory::where('name', 'Legal & Regulatory Risks')->first();

    $this->actingAs($admin)
        ->deleteJson(route('admin.projects.risk-pool.categories.destroy', $cat))
        ->assertOk()
        ->assertJson(['message' => 'Category removed.', 'refresh' => true]);

    expect(\App\Models\RiskCategory::find($cat->id))->toBeNull();
});

it('renders the category management page with delete buttons', function () {
    $admin = adminUser();

    $html = $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.categories.index'))
        ->assertOk()
        ->getContent();

    expect($html)->toContain('data-action="delete"')
        ->and(strpos($html, 'categoryManagerState'))->not->toBeFalse()
        ->and($html)->toContain('name="risk_type"')
        ->and($html)->toContain('data-field="risk_type"')
        ->and(strpos($html, 'Risk Categories'))->not->toBeFalse();
});

it('sorts risks by overall impact level', function () {
    $admin = adminUser();
    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01', 'overall_impact_level' => 'Low']));
    ProjectRiskPool::create(validRiskData(['risk_id' => '2.01', 'risk_category' => 'Procurement Risks', 'overall_impact_level' => 'High']));
    ProjectRiskPool::create(validRiskData(['risk_id' => '3.01', 'risk_category' => 'Labor Risks', 'overall_impact_level' => 'Medium']));

    $risks = $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.index', ['sort' => 'overall_impact_level', 'direction' => 'desc']))
        ->viewData('risks');

    expect($risks->pluck('overall_impact_level')->all())->toBe(['Low', 'Medium', 'High']);
});

it('renders the card grid with correct information and respects the impact filter', function () {
    $admin = adminUser();
    ProjectRiskPool::create(validRiskData(['risk_id' => '1.01', 'risk_category' => 'Financial Risks', 'specific_risk' => 'Advance Payment Delay (Client)', 'overall_impact_level' => 'High']));
    ProjectRiskPool::create(validRiskData(['risk_id' => '3.01', 'risk_category' => 'Labor Risks', 'specific_risk' => 'Labor Shortages', 'overall_impact_level' => 'Low']));

    $html = $this->actingAs($admin)
        ->getJson(route('admin.projects.risk-pool.index', ['partial' => 'cards', 'overall_impact_level' => 'Low']))
        ->assertOk()
        ->json('html');

    expect($html)->toContain('Labor Shortages')
        ->and(strpos($html, 'Advance Payment Delay (Client)'))->toBeFalse();
});

it('allows an admin to delete a risk from the pool', function () {
    $admin = adminUser();
    $risk = ProjectRiskPool::create(validRiskData(['risk_id' => '1.01']));

    $this->actingAs($admin)
        ->deleteJson(route('admin.projects.risk-pool.destroy', $risk))
        ->assertOk()
        ->assertJson(['refresh' => true]);

    expect(ProjectRiskPool::find($risk->id))->toBeNull();
});

it('shows the delete icon only to admins in the risk pool table', function () {
    $admin = adminUser();
    $manager = managerUser();
    $risk = ProjectRiskPool::create(validRiskData(['risk_id' => '1.01']));

    $adminHtml = $this->actingAs($admin)
        ->get(route('admin.projects.risk-pool.index'))
        ->assertViewHas('isAdmin', true)
        ->getContent();

    $managerHtml = $this->actingAs($manager)
        ->get(route('admin.projects.risk-pool.index'))
        ->assertViewHas('isAdmin', false)
        ->getContent();

    expect(strpos($adminHtml, 'title="Delete"'))->not->toBeFalse()
        ->and(strpos($managerHtml, 'title="Delete"'))->toBeFalse();
});

it('blocks non-admins from deleting risk pool items', function () {
    $manager = managerUser();
    $coordinator = coordinatorUser();
    $risk = ProjectRiskPool::create(validRiskData(['risk_id' => '1.01']));

    foreach ([$manager, $coordinator] as $user) {
        $this->actingAs($user)
            ->deleteJson(route('admin.projects.risk-pool.destroy', $risk))
            ->assertForbidden();
    }

    expect(ProjectRiskPool::find($risk->id))->not->toBeNull();
});
