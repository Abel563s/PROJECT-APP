<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eot_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index('is_active');
        });

        $categories = [
            'Design & Documentation Delays',
            'Unforeseen Site Conditions',
            'Weather-Related Delays',
            'Delays Caused by the Employer or Engineer',
            'Regulatory or Legal Events',
            'Force Majeure or Exceptional Events (Clause 19 in FIDIC 1999)',
            'Supply Chain & Logistics',
            'Variations or Change Orders',
            'Suspension of Works',
        ];

        foreach ($categories as $index => $name) {
            DB::table('eot_categories')->insertOrIgnore([
                'name' => $name,
                'display_order' => $index + 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('eot_categories');
    }
};
