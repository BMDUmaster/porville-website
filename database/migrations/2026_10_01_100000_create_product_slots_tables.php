<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ordering windows for selected products: every day, or one specific date.
        Schema::create('product_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->nullable();
            $table->string('type', 10)->default('daily'); // daily | date
            $table->date('slot_date')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_slot_product', function (Blueprint $table) {
            $table->foreignId('product_slot_id')->constrained('product_slots')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->primary(['product_slot_id', 'product_id']);
        });

        // "Notify me" requests, sent when the product's next slot opens.
        Schema::create('product_slot_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            $table->index(['notified_at', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_slot_alerts');
        Schema::dropIfExists('product_slot_product');
        Schema::dropIfExists('product_slots');
    }
};
