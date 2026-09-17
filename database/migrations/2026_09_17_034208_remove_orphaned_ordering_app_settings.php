<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * The "Order On/Off" feature was removed; these settings rows are orphaned leftovers.
     */
    public function up(): void
    {
        AppSetting::whereIn('key', [
            'ordering_active',
            'ordering_inactive_title',
            'ordering_inactive_message',
            'ordering_active_today',
            'ordering_active_tomorrow',
        ])->delete();
    }

    public function down(): void
    {
        // Intentionally not restored — the Ordering feature no longer exists.
    }
};
