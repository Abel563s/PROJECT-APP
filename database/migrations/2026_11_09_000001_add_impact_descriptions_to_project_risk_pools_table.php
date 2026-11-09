<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risk_pools', function (Blueprint $table) {
            $table->text('impact_on_timeline_description')->nullable()->after('impact_on_timeline');
            $table->text('impact_on_cost_description')->nullable()->after('impact_on_cost');
            $table->text('impact_on_quality_description')->nullable()->after('overall_impact_level');
        });
    }

    public function down(): void
    {
        Schema::table('project_risk_pools', function (Blueprint $table) {
            $table->dropColumn([
                'impact_on_timeline_description',
                'impact_on_cost_description',
                'impact_on_quality_description',
            ]);
        });
    }
};
