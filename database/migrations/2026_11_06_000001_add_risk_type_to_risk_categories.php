<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('risk_categories', function (Blueprint $table) {
            $table->string('risk_type')->nullable()->after('code');
        });

        $categories = DB::table('risk_categories')->pluck('name');
        foreach ($categories as $name) {
            $types = DB::table('project_risk_pools')
                ->where('risk_category', $name)
                ->whereIn('risk_type', ['Opportunity', 'Threat'])
                ->distinct()
                ->pluck('risk_type');

            if ($types->count() === 1) {
                DB::table('risk_categories')
                    ->where('name', $name)
                    ->update(['risk_type' => $types->first()]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('risk_categories', function (Blueprint $table) {
            $table->dropColumn('risk_type');
        });
    }
};
