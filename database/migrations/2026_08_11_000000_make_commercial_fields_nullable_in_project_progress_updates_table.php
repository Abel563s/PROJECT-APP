<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_progress_updates', function (Blueprint $table) {
            $table->decimal('revenue_planned', 15, 2)->nullable()->default(null)->change();
            $table->decimal('revenue_actual', 15, 2)->nullable()->default(null)->change();
            $table->decimal('cost_planned', 15, 2)->nullable()->default(null)->change();
            $table->decimal('cost_actual', 15, 2)->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('project_progress_updates', function (Blueprint $table) {
            $table->decimal('revenue_planned', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('revenue_actual', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('cost_planned', 15, 2)->nullable(false)->default(0)->change();
            $table->decimal('cost_actual', 15, 2)->nullable(false)->default(0)->change();
        });
    }
};
