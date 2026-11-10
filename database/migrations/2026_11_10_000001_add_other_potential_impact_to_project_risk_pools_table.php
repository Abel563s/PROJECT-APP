<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risk_pools', function (Blueprint $table) {
            $table->text('other_potential_impact')->nullable()->after('impact_on_quality_description');
        });
    }

    public function down(): void
    {
        Schema::table('project_risk_pools', function (Blueprint $table) {
            $table->dropColumn('other_potential_impact');
        });
    }
};
