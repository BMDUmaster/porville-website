<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('mrp', 10, 2)->nullable()->after('unit_price');
            $table->string('unit')->nullable()->after('mrp');
            $table->decimal('save_offer', 5, 2)->nullable()->after('unit');
            $table->decimal('vendor_amount', 10, 2)->nullable()->after('save_offer');
            $table->decimal('admin_amount', 10, 2)->nullable()->after('vendor_amount');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['mrp', 'unit', 'save_offer', 'vendor_amount', 'admin_amount']);
        });
    }
};
