<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('id');
            $table->decimal('delivery_charge', 10, 2)->default(0)->after('shipping_cost');
            $table->decimal('platform_fee', 10, 2)->default(0)->after('delivery_charge');
            $table->decimal('vendor_total', 10, 2)->default(0)->after('platform_fee');
            $table->decimal('admin_commission', 10, 2)->default(0)->after('vendor_total');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['order_number', 'delivery_charge', 'platform_fee', 'vendor_total', 'admin_commission']);
        });
    }
};
