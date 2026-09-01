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
            'areas' => ['required', 'array', 'min:1'],
            'areas.*.pincode' => ['required', 'regex:/^\d{6}$/'],
            'areas.*.sector' => ['required', 'string', 'max:100'],
        ], [
            'areas.*.pincode.regex' => 'Each PIN Code must contain exactly 6 digits.',
        ]);

        $pinSectors = [];
        foreach ($data['areas'] as $area) {
            $pin = $area['pincode'];
            $sector = trim($area['sector']);
            $pinSectors[$pin][] = $sector;
        }

        $duplicates = collect($pinSectors)->flatMap(fn ($sectors, $pin) => collect($sectors)->map(fn ($sector) => $pin . '|' . strtolower($sector)))
            ->duplicates();
        if ($duplicates->isNotEmpty()) {
            throw ValidationException::withMessages(['areas' => 'The same sector cannot be added more than once for a PIN Code.']);
        }

        DeliveryAreaManager::update($pinSectors);

        return back()->with('success', 'Delivery areas updated successfully.');
    }
}
