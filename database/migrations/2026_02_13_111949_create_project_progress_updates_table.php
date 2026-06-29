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
        Schema::create('project_progress_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->float('progress_planned')->default(0);
            $table->float('progress_actual')->default(0);
            $table->decimal('revenue_planned', 15, 2)->default(0);
            $table->decimal('revenue_actual', 15, 2)->default(0);
            $table->date('completion_date')->nullable();

            // Text fields
            $table->text('top_constraints')->nullable();
            $table->text('client_issue')->nullable();
            $table->text('design_completion_approval')->nullable();
            $table->text('material_submittal_approval')->nullable();
            $table->text('material_delivery')->nullable();
            $table->text('labor')->nullable();
            $table->text('machinery_equipment')->nullable();
            $table->text('subcontractor')->nullable();
            $table->text('finance')->nullable();
            $table->text('operation_constraint')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_progress_updates');
    }
};
