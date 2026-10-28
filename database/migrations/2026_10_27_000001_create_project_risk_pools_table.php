<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_risk_pools', function (Blueprint $table) {
            $table->id();
            $table->string('risk_id')->unique();
            $table->string('risk_category');
            $table->string('specific_risk');
            $table->longText('detailed_risk_explanation')->nullable();
            $table->string('impact_on_timeline')->nullable();
            $table->string('impact_on_cost')->nullable();
            $table->string('overall_impact_level')->nullable();
            $table->longText('stakeholder')->nullable();
            $table->longText('recommended_mitigation_measures')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['risk_category']);
            $table->index(['overall_impact_level']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_risk_pools');
    }
};
