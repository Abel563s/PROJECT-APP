<?php

namespace Database\Seeders;

use App\Models\BusinessUnit;
use App\Models\Sector;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessUnitSeeder extends Seeder
{
    public function run(): void
    {
        $bu = BusinessUnit::create([
            'name' => 'Corporate',
            'code' => 'CORP',
            'description' => 'Corporate Headquarters',
            'is_active' => true,
        ]);

        Sector::create(['business_unit_id' => $bu->id, 'name' => 'Executive', 'is_active' => true]);
        Sector::create(['business_unit_id' => $bu->id, 'name' => 'Finance', 'is_active' => true]);
        Sector::create(['business_unit_id' => $bu->id, 'name' => 'Human Resources', 'is_active' => true]);

        $bu = BusinessUnit::create([
            'name' => 'Engineering',
            'code' => 'ENG',
            'description' => 'Engineering & Development',
            'is_active' => true,
        ]);

        Sector::create(['business_unit_id' => $bu->id, 'name' => 'Software Development', 'is_active' => true]);
        Sector::create(['business_unit_id' => $bu->id, 'name' => 'Infrastructure', 'is_active' => true]);
        Sector::create(['business_unit_id' => $bu->id, 'name' => 'QA & Testing', 'is_active' => true]);

        $bu = BusinessUnit::create([
            'name' => 'Operations',
            'code' => 'OPS',
            'description' => 'Operations & Field Services',
            'is_active' => true,
        ]);

        Sector::create(['business_unit_id' => $bu->id, 'name' => 'Field Operations', 'is_active' => true]);
        Sector::create(['business_unit_id' => $bu->id, 'name' => 'Logistics', 'is_active' => true]);
        Sector::create(['business_unit_id' => $bu->id, 'name' => 'Supply Chain', 'is_active' => true]);
    }
}
