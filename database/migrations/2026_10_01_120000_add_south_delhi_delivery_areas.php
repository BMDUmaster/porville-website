<?php

use App\Support\DeliveryAreaManager;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * One-time data import: adds the South / Central Delhi delivery areas to the
 * admin-managed list (app_settings.delivery_areas). Existing areas are kept,
 * and admins can edit or delete these afterwards from Delivery Areas.
 */
return new class extends Migration
{
    private const AREAS = [
        ['Greater Kailash (GK-I / GK-II)', ['110048']],
        ['Saket', ['110017']],
        ['Hauz Khas', ['110016']],
        ['Green Park', ['110016']],
        ['Vasant Vihar', ['110057']],
        ['Vasant Kunj', ['110070']],
        ['Defence Colony', ['110024']],
        ['South Extension-I', ['110049']],
        ['South Extension-II', ['110049']],
        ['Jor Bagh', ['110003']],
        ['New Friends Colony', ['110025']],
        ['East of Kailash', ['110065']],
        ['Safdarjung Enclave', ['110029']],
        ['Panchsheel Park', ['110017']],
        ['Gulmohar Park', ['110049']],
        ['Hauz Khas Enclave', ['110016']],
        ['Green Park Extension', ['110016']],
        ['Neeti Bagh / Niti Bagh', ['110049']],
        ['Tibet Enclave', ['110016']],
        ['C.R. Park', ['110019']],
        ['Kalkaji', ['110019']],
        ['Malviya Nagar', ['110017']],
        ['Sainik Farm', ['110062']],
        ['Masjid Moth', ['110048']],
        ['Sundar Nagar', ['110003']],
        ["Lutyens' Delhi", ['110001', '110003', '110004']],
        ['Gulmohar Park RT', ['110049']],
        ['Golf Links', ['110003']],
        ['Maharani Bagh', ['110065']],
        ['Anand Lok', ['110049']],
        ['Sarvodaya Enclave', ['110017']],
        ['South X / South Extension', ['110049']],
        ['Hauz Khas Village', ['110016']],
        ['Swami Nagar', ['110017']],
        ['Commonwealth Games Village', ['110092']],
        ['Asian Games Village', ['110049']],
        ['Lodhi Road', ['110003']],
        ['Jangpura / Jungpura', ['110014']],
        ['Jangpura Extension', ['110014']],
        ['Amar Colony', ['110024']],
        ['Kailash Colony', ['110048']],
        ['Lajpat Nagar', ['110024']],
        ['Shivalik, Malviya Nagar', ['110017']],
        ['INA / I.N.A.', ['110023']],
    ];

    public function up(): void
    {
        $areas = $this->currentAreas();

        foreach (self::AREAS as [$sector, $pins]) {
            foreach ($pins as $pin) {
                $existing = array_map('mb_strtolower', $areas[$pin] ?? []);

                if (! in_array(mb_strtolower($sector), $existing, true)) {
                    $areas[$pin][] = $sector;
                }
            }
        }

        DeliveryAreaManager::update($areas);
    }

    public function down(): void
    {
        $areas = $this->currentAreas();

        foreach (self::AREAS as [$sector, $pins]) {
            foreach ($pins as $pin) {
                $areas[$pin] = array_values(array_filter(
                    $areas[$pin] ?? [],
                    fn ($existing) => strcasecmp($existing, $sector) !== 0
                ));
            }
        }

        DeliveryAreaManager::update($areas);
    }

    /**
     * Read straight from the table (not the cache) so nothing stale is merged.
     */
    private function currentAreas(): array
    {
        $value = DB::table('app_settings')->where('key', 'delivery_areas')->value('value');
        $areas = $value ? json_decode($value, true) : [];

        return is_array($areas) ? $areas : [];
    }
};
