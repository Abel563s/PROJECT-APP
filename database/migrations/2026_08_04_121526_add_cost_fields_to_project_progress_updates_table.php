<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('project_progress_updates', function (Blueprint $table) {
            $table->decimal('cost_planned', 15, 2)->default(0)->after('revenue_actual');
            $table->decimal('cost_actual', 15, 2)->default(0)->after('cost_planned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_progress_updates', function (Blueprint $table) {
            $table->dropColumn(['cost_planned', 'cost_actual']);
        });
    }
};
