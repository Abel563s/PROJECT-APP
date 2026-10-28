<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active']);
        });

        // Seed the canonical risk categories so the pool is usable on a fresh
        // migration. This is idempotent and safe to run repeatedly for testing.
        $defaults = [
            ['name' => 'Financial Risks',                  'code' => '1',  'display_order' => 1],
            ['name' => 'Procurement Risks',                'code' => '2',  'display_order' => 2],
            ['name' => 'Labor Risks',                      'code' => '3',  'display_order' => 3],
            ['name' => 'Design & Technical Risks',         'code' => '4',  'display_order' => 4],
            ['name' => 'Safety Risks',                     'code' => '5',  'display_order' => 5],
            ['name' => 'Environmental Risks',              'code' => '6',  'display_order' => 6],
            ['name' => 'Legal & Regulatory Risks',         'code' => '7',  'display_order' => 7],
            ['name' => 'Operational/Fuel Risks',           'code' => '8',  'display_order' => 8],
            ['name' => 'Force Majure',                     'code' => '9',  'display_order' => 9],
            ['name' => 'Schedule',                         'code' => '10', 'display_order' => 10],
            ['name' => 'Strategic/ Corporate Risks',     'code' => '11', 'display_order' => 11],
        ];

        foreach ($defaults as $default) {
            DB::table('risk_categories')->insertOrIgnore([
                'name'           => $default['name'],
                'code'           => $default['code'],
                'display_order'  => $default['display_order'],
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_categories');
    }
};
