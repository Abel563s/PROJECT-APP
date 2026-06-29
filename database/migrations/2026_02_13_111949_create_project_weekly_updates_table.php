<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_weekly_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('contact_person')->nullable();
            $table->string('responsible_person')->nullable();
            $table->date('expected_completion_date')->nullable();
            $table->text('remaining_items')->nullable();
            $table->text('status')->nullable();
            $table->text('activity_planned_next_week')->nullable();
            $table->text('constraints')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_weekly_updates');
    }
};
