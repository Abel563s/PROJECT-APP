<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->string('contract_document_type')->nullable();
            $table->string('project_contract_clause')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('project_risks', function (Blueprint $table) {
            $table->dropColumn([
                'contract_document_type',
                'project_contract_clause',
            ]);
        });
    }
};
