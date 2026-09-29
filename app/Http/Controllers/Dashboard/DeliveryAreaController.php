<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\DeliveryAreaManager;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DeliveryAreaController extends Controller
{
    public function index()
    {
        return view('dashboard.settings.delivery-areas', [
            'pinSectors' => DeliveryAreaManager::pinSectors(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'areas'           => ['nullable', 'array'],
            'areas.*.pincode' => ['required', 'regex:/^\d{6}$/'],
            'areas.*.sector'  => ['required', 'string', 'max:100'],
        ], [
            'areas.*.pincode.required' => 'Every row needs a PIN Code.',
            'areas.*.pincode.regex'    => 'Each PIN Code must contain exactly 6 digits.',
            'areas.*.sector.required'  => 'Every row needs an Area / Sector name.',
        ]);

        $pinSectors = [];
        foreach ($data['areas'] ?? [] as $area) {
            $pinSectors[$area['pincode']][] = trim($area['sector']);
        }

        $duplicates = collect($pinSectors)
            ->flatMap(fn ($sectors, $pin) => collect($sectors)->map(fn ($sector) => $pin . '|' . strtolower($sector)))
            ->duplicates();

        if ($duplicates->isNotEmpty()) {
            [$pin, $sector] = explode('|', $duplicates->first(), 2);
            throw ValidationException::withMessages([
                'areas' => "\"{$sector}\" is added more than once for PIN Code {$pin}.",
            ]);
        }

        DeliveryAreaManager::update($pinSectors);

        return back()->with('success', 'Delivery areas updated successfully.');
    }
}
