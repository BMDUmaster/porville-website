<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('product_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
            $table->string('display_on', 20)->default('home')->after('status');
            $table->index(['product_id', 'status', 'display_on']);
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'status', 'display_on']);
            $table->dropConstrainedForeignId('product_id');
            $table->dropColumn('display_on');
        });
    }
};
