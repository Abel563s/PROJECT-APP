<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->whereIn('role', ['department_attendance_user', 'dceo'])
            ->update(['role' => 'user']);

        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','manager','user','superadmin') NOT NULL DEFAULT 'user'");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','manager','user','department_attendance_user','dceo','superadmin') NOT NULL DEFAULT 'user'");
        }
    }
};
