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
            'todayServiceChargePercent' => ServiceChargeManager::percentage('today'),
            'tomorrowServiceChargePercent' => ServiceChargeManager::percentage('tomorrow'),
            'defaultPercent' => ServiceChargeManager::defaultPercentage(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'today_service_charge_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'tomorrow_service_charge_percent' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $todayPercent = ServiceChargeManager::updatePercentage((float) $data['today_service_charge_percent'], 'today');
        $tomorrowPercent = ServiceChargeManager::updatePercentage((float) $data['tomorrow_service_charge_percent'], 'tomorrow');

        return back()->with(
            'success',
            'Service charge updated. Today: ' . rtrim(rtrim(number_format($todayPercent, 2), '0'), '.') . '%, Tomorrow: ' . rtrim(rtrim(number_format($tomorrowPercent, 2), '0'), '.') . '%.'
        );
    }
}
