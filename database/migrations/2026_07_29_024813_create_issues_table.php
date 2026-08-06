<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sector')->nullable();
            $table->string('project_code')->nullable();
            $table->string('issue_type')->nullable();
            $table->string('issue')->nullable();
            $table->text('description')->nullable();
            $table->text('area_affected')->nullable();
            $table->string('impact')->nullable();
            $table->string('status')->nullable();
            $table->date('raised_date')->nullable();
            $table->string('raised_by')->nullable();
            $table->text('action_plan')->nullable();
            $table->date('planned_resolution_date')->nullable();
            $table->string('action_by')->nullable();
            $table->dateTime('last_updated_date')->nullable();
            $table->date('issue_closed_date')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
