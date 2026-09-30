<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->nullable()->after('tag');
        });

        // Keep the current home page order (by id) for existing parent categories.
        $position = 1;
        DB::table('categories')->whereNull('parent_id')->orderBy('id')->pluck('id')
            ->each(function ($id) use (&$position) {
                DB::table('categories')->where('id', $id)->update(['sort_order' => $position++]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('sort_order');
        });
    }
};
