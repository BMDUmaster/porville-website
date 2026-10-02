<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Sub admins: staff accounts that only see the admin modules assigned
     * to them (stored as a JSON list of module keys in `permissions`).
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role ENUM('customer', 'admin', 'sub_admin') NOT NULL DEFAULT 'customer'");
        }

        Schema::table('users', function (Blueprint $table) {
            $table->text('permissions')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE users SET role = 'customer' WHERE role = 'sub_admin'");
            DB::statement("ALTER TABLE users MODIFY role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer'");
        }
    }
};
