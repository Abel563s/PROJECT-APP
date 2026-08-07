<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_progress_updates', function (Blueprint $table) {
            $table->date('data_date')->nullable()->after('completion_date');
        });
    }

    public function down(): void
    {
        Schema::table('project_progress_updates', function (Blueprint $table) {
            $table->dropColumn('data_date');
        });
    }
};
