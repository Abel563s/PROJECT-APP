<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('manage_materials')) {
            Schema::create('manage_materials', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('material_code')->unique()->nullable(false);
                $table->string('material_name');
                $table->string('supplier_name')->nullable();
                $table->string('discipline')->nullable();
                $table->string('procurement_product')->nullable();
                $table->string('supply_route')->nullable();
                $table->string('approval')->nullable();
                $table->string('procurement_type')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('manage_materials');
    }
};
