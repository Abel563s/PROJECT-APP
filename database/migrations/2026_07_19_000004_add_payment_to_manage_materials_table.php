<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('manage_materials')) {
            Schema::table('manage_materials', function (Blueprint $table) {
                $table->date('payment')->nullable()->after('lc');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('manage_materials')) {
            Schema::table('manage_materials', function (Blueprint $table) {
                $table->dropColumn('payment');
            });
        }
    }
};
