<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->string('pool_risk_category')->nullable()->after('risk_category');
            $table->string('pool_risk_id')->nullable()->after('pool_risk_category');
            $table->index('pool_risk_id');
        });

        // risk_category is now optional since it is sourced from the risk pool
        Schema::table('project_risks', function (Blueprint $table) {
            $table->string('risk_category')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->string('risk_category')->nullable(false)->change();
            $table->dropIndex(['pool_risk_id']);
            $table->dropColumn(['pool_risk_category', 'pool_risk_id']);
        });
    }
};
