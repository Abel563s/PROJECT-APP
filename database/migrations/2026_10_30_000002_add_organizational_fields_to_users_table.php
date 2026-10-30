<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('business_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sector_id')->nullable()->constrained()->nullOnDelete();
        });

        $this->updateRoleEnum();
    }

    private function updateRoleEnum(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement("ALTER TABLE users RENAME COLUMN role TO role_temp");
            DB::statement("ALTER TABLE users ADD COLUMN role TEXT NOT NULL DEFAULT 'user'");
            DB::statement("UPDATE users SET role = role_temp");
            DB::statement("ALTER TABLE users DROP COLUMN role_temp");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','manager','user','department_attendance_user','dceo','superadmin') NOT NULL DEFAULT 'user'");
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['business_unit_id']);
            $table->dropForeign(['sector_id']);
            $table->dropColumn(['business_unit_id', 'sector_id']);
        });

        if ($driver === 'sqlite') {
            DB::statement("ALTER TABLE users RENAME COLUMN role TO role_temp");
            DB::statement("ALTER TABLE users ADD COLUMN role TEXT NOT NULL DEFAULT 'user'");
            DB::statement("UPDATE users SET role = role_temp");
            DB::statement("ALTER TABLE users DROP COLUMN role_temp");
        } elseif ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','manager','user','department_attendance_user','dceo') NOT NULL DEFAULT 'user'");
        }
    }
};
