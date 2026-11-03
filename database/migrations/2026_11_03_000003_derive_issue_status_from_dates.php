<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('issues')
            ->whereNotNull('issue_closed_date')
            ->update(['status' => 'Closed']);

        DB::table('issues')
            ->whereNull('issue_closed_date')
            ->whereNotNull('planned_resolution_date')
            ->update(['status' => 'Open']);

        DB::table('issues')
            ->whereNull('issue_closed_date')
            ->whereNull('planned_resolution_date')
            ->update(['status' => 'Raised']);
    }

    public function down(): void
    {
    }
};
