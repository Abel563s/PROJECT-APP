<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('risk_category');
            $table->text('activity');
            $table->string('risk_type');
            $table->string('risk_name');
            $table->text('risk_description');
            $table->unsignedTinyInteger('likelihood');
            $table->unsignedTinyInteger('severity');
            $table->string('project_impact')->nullable();
            $table->boolean('intent_to_claim')->default(false);
            $table->decimal('most_likely_cost_impact', 15, 2)->nullable();
            $table->unsignedInteger('most_likely_time_impact')->nullable();
            $table->string('time_impact_unit')->nullable();
            $table->string('stakeholder')->nullable();
            $table->string('risk_frequency')->nullable();
            $table->date('impact_date')->nullable();
            $table->string('risk_urgency')->nullable();
            $table->text('existing_control')->nullable();
            $table->text('residual_risk')->nullable();
            $table->string('response_strategy')->nullable();
            $table->text('mitigation_plan')->nullable();
            $table->text('contingency_plan')->nullable();
            $table->string('risk_owner')->nullable();
            $table->string('owner_name')->nullable();
            $table->date('planned_action_date')->nullable();
            $table->date('date_last_updated')->nullable();
            $table->text('tracking_comments')->nullable();
            $table->string('action_status')->default('Open');
            $table->date('actual_action_date')->nullable();
            $table->unsignedInteger('exposure')->nullable();
            $table->string('exposure_unit')->nullable();
            $table->text('recommendation')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'action_status']);
            $table->index(['project_id', 'risk_category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_risks');
    }
};
