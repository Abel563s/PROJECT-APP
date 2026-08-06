<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->change();
        });

        \DB::table('projects')->whereIn('status', ['active', 'inactive'])->update(['status' => 'in progress']);

        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['in progress', 'completed'])->default('in progress')->change();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status')->change();
        });

        \DB::table('projects')->whereIn('status', ['in progress', 'completed'])->update(['status' => 'active']);

        Schema::table('projects', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
        });
    }
};
