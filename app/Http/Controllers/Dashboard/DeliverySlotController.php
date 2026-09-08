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
            'previewSlots' => DeliverySlotManager::options('today'),
            'previewTomorrowSlots' => DeliverySlotManager::options('tomorrow'),
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
            
            'tomorrow_fixed_slots_text' => ['nullable', 'string'],
            'tomorrow_evening_start' => ['nullable', 'date_format:H:i'],
            'tomorrow_last_end' => ['nullable', 'date_format:H:i'],
            'tomorrow_slot_duration_hours' => ['required', 'integer', 'min:1', 'max:6'],

            'no_slot_popup_title' => ['nullable', 'string', 'max:255'],
            'no_slot_popup_description' => ['nullable', 'string', 'max:2000'],
        ]);

        $fixedSlots = $this->parseFixedSlots((string) ($data['fixed_slots_text'] ?? ''));
        $eveningStart = $data['evening_start'] ?? null;
        $lastEnd = $data['last_end'] ?? null;

        $tomorrowFixedSlots = $this->parseFixedSlots((string) ($data['tomorrow_fixed_slots_text'] ?? ''));
        $tomorrowEveningStart = $data['tomorrow_evening_start'] ?? null;
        $tomorrowLastEnd = $data['tomorrow_last_end'] ?? null;

        if (($eveningStart && ! $lastEnd) || (! $eveningStart && $lastEnd)) {
            throw ValidationException::withMessages([
                'evening_start' => 'Both evening start and last slot end are required together for Today.',
            ]);
        }

        if ($eveningStart && $lastEnd && $lastEnd <= $eveningStart) {
            throw ValidationException::withMessages([
                'last_end' => 'Last slot end must be later than evening start for Today.',
            ]);
        }

        if (($tomorrowEveningStart && ! $tomorrowLastEnd) || (! $tomorrowEveningStart && $tomorrowLastEnd)) {
            throw ValidationException::withMessages([
                'tomorrow_evening_start' => 'Both evening start and last slot end are required together for Tomorrow.',
            ]);
        }

        if ($tomorrowEveningStart && $tomorrowLastEnd && $tomorrowLastEnd <= $tomorrowEveningStart) {
            throw ValidationException::withMessages([
                'tomorrow_last_end' => 'Last slot end must be later than evening start for Tomorrow.',
            ]);
        }

        DeliveryChargeManager::updateAmount((float) $data['delivery_charge']);

        DeliverySlotManager::updateSettings([
            'fixed_slots' => $fixedSlots,
            'evening_start' => $eveningStart,
            'last_end' => $lastEnd,
            'slot_duration_hours' => (int) $data['slot_duration_hours'],

            'tomorrow_fixed_slots' => $tomorrowFixedSlots,
            'tomorrow_evening_start' => $tomorrowEveningStart,
            'tomorrow_last_end' => $tomorrowLastEnd,
            'tomorrow_slot_duration_hours' => (int) $data['tomorrow_slot_duration_hours'],

            'no_slot_popup_title' => $data['no_slot_popup_title'] ?? null,
            'no_slot_popup_description' => $data['no_slot_popup_description'] ?? null,
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
