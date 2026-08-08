<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Seed default ordering settings if not already present
        $defaults = [
            'ordering_active'           => '1',
            'ordering_inactive_title'   => 'Orders Temporarily Paused',
            'ordering_inactive_message' => 'We are currently not accepting new orders. Please check back soon. We apologize for the inconvenience.',
        ];

        foreach ($defaults as $key => $value) {
            AppSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    public function down(): void
    {
        AppSetting::whereIn('key', [
            'ordering_active',
            'ordering_inactive_title',
            'ordering_inactive_message',
        ])->delete();
    }
};
