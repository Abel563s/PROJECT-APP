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
        Schema::table('project_payments', function (Blueprint $table) {
            $table->decimal('submitted_amount', 15, 2)->nullable()->after('certificate_date');
            $table->date('submitted_date')->nullable()->after('submitted_amount');
            $table->decimal('certified_amount', 15, 2)->nullable()->after('submitted_date');
            $table->date('certified_date')->nullable()->after('certified_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_payments', function (Blueprint $table) {
            $table->dropColumn(['submitted_amount', 'submitted_date', 'certified_amount', 'certified_date']);
        });
    }
};
