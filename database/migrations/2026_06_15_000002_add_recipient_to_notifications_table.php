<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('recipient_id')->nullable()->after('sent_by')->constrained('users')->nullOnDelete();
            $table->timestamp('read_at')->nullable()->after('recipient_id');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recipient_id');
            $table->dropColumn('read_at');
        });
    }
};
