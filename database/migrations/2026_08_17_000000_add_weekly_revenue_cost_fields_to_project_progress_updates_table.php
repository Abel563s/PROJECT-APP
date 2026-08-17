<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_progress_updates', function (Blueprint $table) {
            $table->decimal('revenue_planned_weekly', 15, 2)->nullable()->default(null)->after('revenue_actual');
            $table->decimal('revenue_actual_weekly', 15, 2)->nullable()->default(null)->after('revenue_planned_weekly');
            $table->decimal('cost_planned_weekly', 15, 2)->nullable()->default(null)->after('cost_actual');
            $table->decimal('cost_actual_weekly', 15, 2)->nullable()->default(null)->after('cost_planned_weekly');
        });
    }

    public function down(): void
    {
        Schema::table('project_progress_updates', function (Blueprint $table) {
            $table->dropColumn(['revenue_planned_weekly', 'revenue_actual_weekly', 'cost_planned_weekly', 'cost_actual_weekly']);
        });
    }
};
