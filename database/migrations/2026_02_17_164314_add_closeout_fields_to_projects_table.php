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
        Schema::table('projects', function (Blueprint $table) {
            // Project Info (remaining)
            $table->string('location')->nullable();
            $table->string('project_manager')->nullable();

            // Financial Summary (remaining)
            $table->decimal('total_certified', 15, 2)->default(0);
            $table->decimal('total_paid', 15, 2)->default(0);
            $table->decimal('retention', 15, 2)->default(0);

            // Technical Completion
            $table->decimal('physical_completion_percent', 5, 2)->default(0);
            $table->date('handover_date')->nullable();
            $table->string('defects_liability_period')->nullable();
            $table->enum('snag_list_status', ['Pending', 'In Progress', 'Completed'])->default('Pending');
            $table->enum('completion_certificate_status', ['Pending', 'Issued'])->default('Pending');

            // Document Checklist
            $table->enum('doc_completion_cert_status', ['Pending', 'Uploaded', 'Approved'])->default('Pending');
            $table->enum('doc_as_built_status', ['Pending', 'Uploaded', 'Approved'])->default('Pending');
            $table->enum('doc_final_boq_status', ['Pending', 'Uploaded', 'Approved'])->default('Pending');
            $table->enum('doc_handover_report_status', ['Pending', 'Uploaded', 'Approved'])->default('Pending');

            // Lessons Learned
            $table->text('challenges_faced')->nullable();
            $table->text('financial_performance_notes')->nullable();
            $table->text('schedule_performance_notes')->nullable();
            $table->text('recommendations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'project_manager',
                'total_certified',
                'total_paid',
                'retention',
                'physical_completion_percent',
                'handover_date',
                'defects_liability_period',
                'snag_list_status',
                'completion_certificate_status',
                'doc_completion_cert_status',
                'doc_as_built_status',
                'doc_final_boq_status',
                'doc_handover_report_status',
                'challenges_faced',
                'financial_performance_notes',
                'schedule_performance_notes',
                'recommendations'
            ]);
        });
    }
};
