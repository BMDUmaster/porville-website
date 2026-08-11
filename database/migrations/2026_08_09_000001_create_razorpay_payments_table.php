<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('razorpay_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('razorpay_order_id')->unique();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_signature')->nullable();
            $table->unsignedBigInteger('amount');          // in paise
            $table->string('status');                      // initiated|paid|failed|cancelled
            $table->timestamps();

            $table->index('razorpay_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('razorpay_payments');
    }
};
