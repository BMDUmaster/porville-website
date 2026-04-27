<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'delivery_boy_id')) {
                $table->foreignId('delivery_boy_id')->nullable()->after('user_id')->constrained('delivery_boys')->nullOnDelete();
            }
        });

        DB::table('orders')->where('status', 'shipped')->update([
            'status' => 'out_for_delivery',
        ]);

        DB::statement("ALTER TABLE orders MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::table('orders')->where('status', 'out_for_delivery')->update([
            'status' => 'shipped',
        ]);

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','confirmed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending'");

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'delivery_boy_id')) {
                $table->dropConstrainedForeignId('delivery_boy_id');
            }
        });
    }
};
