<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->enum('project_type', [
                'Building',
                'Fit-Out',
                'Infrastructure',
                'Water & Energy',
                'Mixed (Bui/Road)',
                'Mixed (Bui/Fit-Out)',
            ])->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('projects')->where('project_type', 'Water & Energy')->update(['project_type' => 'Infrastructure']);

        Schema::table('projects', function (Blueprint $table) {
            $table->enum('project_type', [
                'Building',
                'Fit-Out',
                'Infrastructure',
                'Mixed (Bui/Road)',
                'Mixed (Bui/Fit-Out)',
            ])->nullable()->change();
        });
    }
};
