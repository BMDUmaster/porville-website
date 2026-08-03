<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('description')->constrained()->nullOnDelete();
            $table->dateTime('starts_at')->nullable()->after('per_user_limit');
            $table->index(['entry_type', 'product_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex(['entry_type', 'product_id', 'is_active']);
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn('starts_at');
        });
    }
};
