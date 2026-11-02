<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contract_clauses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('row_number')->unique();
            $table->string('item', 1000);
            $table->text('ppa')->nullable();
            $table->text('fidic_red')->nullable();
            $table->text('fidic_yellow')->nullable();
            $table->text('fidic_silver')->nullable();
            $table->longText('planner_explanation');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_clauses');
    }
};
