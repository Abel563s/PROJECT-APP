<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Project::create([
            'project_code' => 'BUI-001',
            'project_name' => 'Skyline Executive Residency',
            'project_type' => 'Building',
            'delivery_method' => 'DBB',
            'project_client' => 'City Real Estate Dev',
            'consultant' => 'Apex Architects',
            'consultancy_sector' => 'Private',
            'scope' => 'Luxury 40-story residential complex with subterranean parking and smart grid integration.',
            'contract_budget' => 450000000.00,
            'variation' => 12500000.00,
            'supplementary' => 5000000.00,
            'total_allowable_cost' => 420000000.00,
            'cost_at_completion' => 415000000.00,
            'baseline_start_date' => '2025-01-01',
            'baseline_finish_date' => '2026-12-31',
            'actual_start_date' => '2025-01-15',
            'closing_status' => 'Not Completed'
        ]);

        \App\Models\Project::create([
            'project_code' => 'INF-052',
            'project_name' => 'Metro Expansion Phase II',
            'project_type' => 'Infrastructure',
            'delivery_method' => 'DB',
            'project_client' => 'Ministry of Transport',
            'consultant' => 'Global Transit Eng',
            'consultancy_sector' => 'Government',
            'scope' => 'Construction of 12km elevated rail line including 8 integrated transit hubs.',
            'contract_budget' => 1250000000.00,
            'variation' => 45000000.00,
            'supplementary' => 0.00,
            'total_allowable_cost' => 1100000000.00,
            'cost_at_completion' => 1150000000.00,
            'baseline_start_date' => '2024-06-01',
            'baseline_finish_date' => '2026-06-01',
            'actual_start_date' => '2024-06-10',
            'closing_status' => 'Not Completed'
        ]);

        \App\Models\Project::create([
            'project_code' => 'FIT-009',
            'project_name' => 'Corporate HQ Fit-Out',
            'project_type' => 'Fit-Out',
            'delivery_method' => 'DB-LS',
            'project_client' => 'NexGen Tech Hub',
            'consultant' => 'Interior Logic',
            'consultancy_sector' => 'Private',
            'scope' => 'High-end interior fit-out for 5 floors of Grade A office space including auditorium.',
            'contract_budget' => 85000000.00,
            'variation' => 2000000.00,
            'supplementary' => 1500000.00,
            'total_allowable_cost' => 82000000.00,
            'cost_at_completion' => 80000000.00,
            'baseline_start_date' => '2025-03-01',
            'baseline_finish_date' => '2025-09-01',
            'actual_start_date' => '2025-03-01',
            'actual_finish_date' => '2025-08-15',
            'closing_status' => 'FA Received',
            'fa_received_at' => '2025-08-20'
        ]);
    }
}
