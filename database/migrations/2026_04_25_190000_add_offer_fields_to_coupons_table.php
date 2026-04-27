<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('entry_type')->default('coupon')->after('id');
            $table->string('title')->nullable()->after('entry_type');
            $table->text('description')->nullable()->after('title');
        });

        DB::table('coupons')->whereNull('entry_type')->update(['entry_type' => 'coupon']);
    }

    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn(['entry_type', 'title', 'description']);
        });
    }
};
