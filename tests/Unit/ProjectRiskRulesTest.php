<?php

use App\Models\ProjectRisk;
use Carbon\Carbon;

beforeEach(function () {
    Carbon::setTestNow('2026-11-05 12:00:00');
});

afterEach(function () {
    Carbon::setTestNow();
});

it('calculates risk exposure from materialized date to actual date or today', function () {
    expect(ProjectRisk::calculateExposureDays('2026-11-01', null))->toBe(4)
        ->and(ProjectRisk::calculateExposureDays('2026-11-01', '2026-11-03'))->toBe(2)
        ->and(ProjectRisk::calculateExposureDays('2026-11-06', null))->toBe(-1)
        ->and(ProjectRisk::calculateExposureDays('2026-11-04', '2026-11-03'))->toBe(-1)
        ->and(ProjectRisk::calculateExposureDays(null, null))->toBeNull();
});

it('offers response strategies for the selected risk type', function () {
    expect(ProjectRisk::RESPONSE_STRATEGIES)->toBe([
        'Threat' => ['Avoid', 'Mitigate', 'Transfer', 'Accept', 'Escalate'],
        'Opportunity' => ['Exploit', 'Enhance', 'Share'],
    ]);
});
