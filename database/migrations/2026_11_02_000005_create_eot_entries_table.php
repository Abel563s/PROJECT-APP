<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eot_entries', function (Blueprint $table) {
            $table->id();
            $table->string('eot_id')->unique();
            $table->foreignId('eot_category_id')->constrained('eot_categories')->restrictOnDelete();
            $table->longText('detail_reason');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eot_entries');
    }
};
