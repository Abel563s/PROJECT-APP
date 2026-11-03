<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->unsignedInteger('most_likely_quality_impact')->nullable();
            $table->string('resolution_by')->nullable();
            $table->date('materialized_date')->nullable();
            $table->foreignId('eot_entry_id')->nullable()->constrained('eot_entries')->nullOnDelete();
        });
        Schema::table('project_risks', function (Blueprint $table) {
            $table->string('action_status')->default('Risk')->change();
        });

        DB::table('project_risks')
            ->whereNull('resolution_by')
            ->whereNotNull('stakeholder')
            ->update(['resolution_by' => DB::raw('stakeholder')]);

        DB::table('project_risks')->where('action_status', 'Open')->update(['action_status' => 'Risk']);
        DB::table('project_risks')->where('action_status', 'Inprogress')->update(['action_status' => 'Issue']);
        DB::table('project_risks')->where('action_status', 'Closed')->update(['action_status' => 'Resolved']);
    }

    public function down(): void
    {
        DB::table('project_risks')->where('action_status', 'Risk')->update(['action_status' => 'Open']);
        DB::table('project_risks')->where('action_status', 'Issue')->update(['action_status' => 'Inprogress']);
        DB::table('project_risks')->where('action_status', 'Resolved')->update(['action_status' => 'Closed']);

        Schema::table('project_risks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('eot_entry_id');
            $table->dropColumn(['most_likely_quality_impact', 'resolution_by', 'materialized_date']);
            $table->string('action_status')->default('Open')->change();
        });
    }
};
