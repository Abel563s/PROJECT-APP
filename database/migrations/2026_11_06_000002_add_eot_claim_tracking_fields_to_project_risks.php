<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->longText('detail_reason_for_eot')->nullable();
            $table->longText('project_case_event_description')->nullable();
            $table->string('reference_contract_clause_no')->nullable();
            $table->longText('affected_activities')->nullable();
            $table->string('itc_notification_status')->nullable();
            $table->date('itc_notification_date')->nullable();
            $table->string('eot_status')->nullable();
            $table->date('eot_submitted_date')->nullable();
            $table->date('eot_response_date')->nullable();
            $table->unsignedInteger('eot_requested_days')->nullable();
            $table->unsignedInteger('eot_approved_days')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->dropColumn([
                'detail_reason_for_eot',
                'project_case_event_description',
                'reference_contract_clause_no',
                'affected_activities',
                'itc_notification_status',
                'itc_notification_date',
                'eot_status',
                'eot_submitted_date',
                'eot_response_date',
                'eot_requested_days',
                'eot_approved_days',
            ]);
        });
    }
};
