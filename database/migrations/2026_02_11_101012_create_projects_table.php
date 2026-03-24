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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('custom_id')->unique(); // PRJ-0001
            $table->string('project_code')->nullable();
            $table->string('project_name');
            $table->enum('project_type', ['Building', 'Fit-Out', 'Infrastructure', 'Mixed (Bui/Road)', 'Mixed (Bui/Fit-Out)'])->nullable();
            $table->enum('delivery_method', ['DB', 'DBB', 'DB-LS', 'DB-ADM', 'DBB-ADM', 'DB-CP'])->nullable();

            // Stakeholders
            $table->string('project_client')->nullable();
            $table->string('consultant')->nullable();
            $table->enum('consultancy_sector', ['Government', 'Private', 'Client / Engineering Team'])->nullable();
            $table->text('scope')->nullable();

            // Financial Control
            $table->decimal('contract_budget', 15, 2)->default(0);
            $table->decimal('variation', 15, 2)->default(0);
            $table->decimal('supplementary', 15, 2)->default(0);
            $table->decimal('total_allowable_cost', 15, 2)->default(0);
            $table->decimal('cost_at_completion', 15, 2)->default(0);

            // Schedule
            $table->date('baseline_start_date')->nullable();
            $table->date('baseline_finish_date')->nullable();
            $table->date('actual_start_date')->nullable();
            $table->date('actual_finish_date')->nullable();
            $table->date('approved_eot')->nullable(); // Extended Date

            // Closeout & Status
            $table->string('revision_number')->nullable();
            $table->enum('closing_status', ['FA Received', 'PA Received', 'PPA Received', 'Snag / Di-Snag', 'Waiting for PA', 'Not Completed'])->default('Not Completed');
            $table->date('ppa_received_at')->nullable();
            $table->date('pa_received_at')->nullable();
            $table->date('fa_received_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
