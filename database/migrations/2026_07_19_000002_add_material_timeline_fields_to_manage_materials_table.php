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
                $table->date('design_planned')->nullable()->after('procurement_type');
                $table->date('design_actual')->nullable()->after('design_planned');
                $table->date('spec_preparation')->nullable()->after('design_actual');
                $table->date('request_order')->nullable()->after('spec_preparation');
                $table->date('supplier_identification')->nullable()->after('request_order');
                $table->date('material_submittal')->nullable()->after('supplier_identification');
                $table->date('material_approval')->nullable()->after('material_submittal');
                $table->date('finance')->nullable()->after('material_approval');
                $table->date('lc')->nullable()->after('finance');
                $table->date('production_comp')->nullable()->after('lc');
                $table->date('shipment_of_material')->nullable()->after('production_comp');
                $table->date('logistics_to_dry_port')->nullable()->after('shipment_of_material');
                $table->date('clearance_of_material')->nullable()->after('logistics_to_dry_port');
                $table->date('delivery_to_site')->nullable()->after('clearance_of_material');
                $table->date('planned_start_date')->nullable()->after('delivery_to_site');
                $table->integer('production_days')->nullable()->after('planned_start_date');
                $table->integer('shipment_days')->nullable()->after('production_days');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('manage_materials')) {
            Schema::table('manage_materials', function (Blueprint $table) {
                $table->dropColumn([
                    'design_planned','design_actual','spec_preparation','request_order','supplier_identification',
                    'material_submittal','material_approval','finance','lc','production_comp','shipment_of_material',
                    'logistics_to_dry_port','clearance_of_material','delivery_to_site','planned_start_date',
                    'production_days','shipment_days'
                ]);
            });
        }
    }
};
