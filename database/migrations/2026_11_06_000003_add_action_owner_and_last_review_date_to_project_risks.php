<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->string('action_owner')->nullable();
            $table->date('last_review_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->dropColumn(['action_owner', 'last_review_date']);
        });
    }
};
