<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\ServiceChargeManager;
use Illuminate\Http\Request;

class ServiceChargeController extends Controller
{
    public function index()
    {
        return view('dashboard.settings.service-charge', [
            'serviceChargePercent' => ServiceChargeManager::percentage(),
            'defaultPercent' => ServiceChargeManager::defaultPercentage(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'service_charge_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $savedPercent = ServiceChargeManager::updatePercentage((float) $data['service_charge_percent']);

        return back()->with('success', 'Service charge updated to ' . rtrim(rtrim(number_format($savedPercent, 2), '0'), '.') . '%.');
    }
}
