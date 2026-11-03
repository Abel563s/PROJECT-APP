<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risk_pools', function (Blueprint $table) {
            $table->string('risk_type')->nullable()->after('risk_id');
        });
    }

    public function down(): void
    {
        Schema::table('project_risk_pools', function (Blueprint $table) {
            $table->dropColumn('risk_type');
        });
    }
};
