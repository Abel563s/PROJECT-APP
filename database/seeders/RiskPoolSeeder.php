<?php

namespace Database\Seeders;

use App\Models\ProjectRiskPool;
use Illuminate\Database\Seeder;

class RiskPoolSeeder extends Seeder
{
    public function run(): void
    {
        $risks = [
            ['risk_id' => '1.01', 'risk_category' => 'Financial Risks',                'specific_risk' => 'Advance Payment Delay (Client)', 'stakeholder' => 'Client, Project Manager, Senior Management'],
            ['risk_id' => '1.02', 'risk_category' => 'Financial Risks',                'specific_risk' => 'Cash flow Shortage',             'stakeholder' => 'Project Manager, Senior Management'],
            ['risk_id' => '2.01', 'risk_category' => 'Procurement Risks',              'specific_risk' => 'Material Delays',                 'stakeholder' => 'Procurement Manager, Supplier'],
            ['risk_id' => '3.01', 'risk_category' => 'Labor Risks',                    'specific_risk' => 'Labor Shortages',                 'stakeholder' => 'HR, Site Manager'],
            ['risk_id' => '4.01', 'risk_category' => 'Design & Technical Risks',       'specific_risk' => 'Design Errors or Omissions',      'stakeholder' => 'Design Team, Consultant'],
            ['risk_id' => '5.01', 'risk_category' => 'Safety Risks',                   'specific_risk' => 'Workplace Accidents',             'stakeholder' => 'HSE Department'],
            ['risk_id' => '6.01', 'risk_category' => 'Environmental Risks',            'specific_risk' => 'Environmental Permitting',        'stakeholder' => 'HSE Department'],
            ['risk_id' => '8.01', 'risk_category' => 'Operational/Fuel Risks',         'specific_risk' => 'Equipment Failures',              'stakeholder' => 'Operations Manager'],
            ['risk_id' => '9.01', 'risk_category' => 'Force Majure',                   'specific_risk' => 'Political Instability',           'stakeholder' => 'Senior Management'],
            ['risk_id' => '10.01','risk_category' => 'Schedule',                       'specific_risk' => 'Inaccurate Project duration',     'stakeholder' => 'Planning and Management'],
        ];

        foreach ($risks as $risk) {
            ProjectRiskPool::firstOrCreate(
                ['risk_id' => $risk['risk_id']],
                [
                    'risk_category' => $risk['risk_category'],
                    'specific_risk' => $risk['specific_risk'],
                    'stakeholder' => $risk['stakeholder'],
                    'detailed_risk_explanation' => null,
                    'impact_on_timeline' => null,
                    'impact_on_cost' => null,
                    'overall_impact_level' => null,
                    'recommended_mitigation_measures' => null,
                    'created_by' => null,
                ]
            );
        }
    }
}
