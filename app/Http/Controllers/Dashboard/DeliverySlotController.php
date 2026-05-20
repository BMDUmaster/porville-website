<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\DeliveryChargeManager;
use App\Support\DeliverySlotManager;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeliverySlotController extends Controller
{
    public function index()
    {
        $settings = DeliverySlotManager::settings();

        return view('dashboard.settings.delivery-slots', [
            'settings' => $settings,
            'previewSlots' => DeliverySlotManager::options(),
            'deliveryCharge' => DeliveryChargeManager::amount(),
            'defaultDeliveryCharge' => DeliveryChargeManager::defaultAmount(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'delivery_charge' => ['required', 'numeric', 'min:0'],
            'fixed_slots_text' => ['nullable', 'string'],
            'evening_start' => ['nullable', 'date_format:H:i'],
            'last_end' => ['nullable', 'date_format:H:i'],
            'slot_duration_hours' => ['required', 'integer', 'min:1', 'max:6'],
        ]);

        $fixedSlots = $this->parseFixedSlots((string) ($data['fixed_slots_text'] ?? ''));
        $eveningStart = $data['evening_start'] ?? null;
        $lastEnd = $data['last_end'] ?? null;

        if (($eveningStart && ! $lastEnd) || (! $eveningStart && $lastEnd)) {
            throw ValidationException::withMessages([
                'evening_start' => 'Both evening start and last slot end are required together.',
            ]);
        }

        if ($eveningStart && $lastEnd && $lastEnd <= $eveningStart) {
            throw ValidationException::withMessages([
                'last_end' => 'Last slot end must be later than evening start.',
            ]);
        }

        if (empty($fixedSlots) && ! $eveningStart && ! $lastEnd) {
            throw ValidationException::withMessages([
                'fixed_slots_text' => 'Please keep at least one delivery slot available.',
            ]);
        }

        DeliveryChargeManager::updateAmount((float) $data['delivery_charge']);

        DeliverySlotManager::updateSettings([
            'fixed_slots' => $fixedSlots,
            'evening_start' => $eveningStart,
            'last_end' => $lastEnd,
            'slot_duration_hours' => (int) $data['slot_duration_hours'],
        ]);

        return back()->with('success', 'Delivery slots and delivery charge updated successfully.');
    }

    private function parseFixedSlots(string $value): array
    {
        $lines = preg_split('/\r\n|\r|\n/', trim($value)) ?: [];
        $slots = [];

        foreach ($lines as $index => $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            if (! preg_match('/^(\d{2}:\d{2})\s*-\s*(\d{2}:\d{2})$/', $line, $matches)) {
                throw ValidationException::withMessages([
                    'fixed_slots_text' => 'Use one slot per line in HH:MM-HH:MM format. Problem on line ' . ($index + 1) . '.',
                ]);
            }

            if (! $this->isValidTime($matches[1]) || ! $this->isValidTime($matches[2])) {
                throw ValidationException::withMessages([
                    'fixed_slots_text' => 'Please use valid 24-hour time values. Problem on line ' . ($index + 1) . '.',
                ]);
            }

            if ($matches[2] <= $matches[1]) {
                throw ValidationException::withMessages([
                    'fixed_slots_text' => 'Each fixed slot end time must be later than its start time. Problem on line ' . ($index + 1) . '.',
                ]);
            }

            $slots[] = [
                'start' => $matches[1],
                'end' => $matches[2],
            ];
        }

        return $slots;
    }

    private function isValidTime(string $value): bool
    {
        return DateTimeImmutable::createFromFormat('H:i', $value) !== false;
    }
}
