<?php

use App\Models\AppSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $legacyActive = AppSetting::where('key', 'ordering_active')->value('value') ?? '1';

        foreach ([
            'ordering_active_today' => $legacyActive,
            'ordering_active_tomorrow' => $legacyActive,
        ] as $key => $value) {
            AppSetting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    public function down(): void
    {
        AppSetting::whereIn('key', [
            'ordering_active_today',
            'ordering_active_tomorrow',
        ])->delete();
    }
};
