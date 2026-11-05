<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->text('most_likely_quality_impact_description')->nullable();
        });

        Schema::table('eot_entries', function (Blueprint $table) {
            $table->string('contract_reference_id')->nullable()->after('eot_category_id');
        });
    }

    public function down(): void
    {
        Schema::table('eot_entries', function (Blueprint $table) {
            $table->dropColumn('contract_reference_id');
        });

        Schema::table('project_risks', function (Blueprint $table) {
            $table->dropColumn('most_likely_quality_impact_description');
        });
    }
};
