<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\OrderingManager;
use Illuminate\Http\Request;

class OrderingSettingController extends Controller
{
    public function index()
    {
        $isTodayActive = OrderingManager::isActiveForDay('today');
        $isTomorrowActive = OrderingManager::isActiveForDay('tomorrow');

        return view('dashboard.settings.ordering', [
            'isActive' => $isTodayActive && $isTomorrowActive,
            'isTodayActive' => $isTodayActive,
            'isTomorrowActive' => $isTomorrowActive,
            'title'    => OrderingManager::inactiveTitle(),
            'message'  => OrderingManager::inactiveMessage(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'ordering_active_today'     => 'nullable|boolean',
            'ordering_active_tomorrow'  => 'nullable|boolean',
            'ordering_inactive_title'   => 'required|string|max:120',
            'ordering_inactive_message' => 'required|string|max:500',
        ]);

        OrderingManager::update(
            (bool) ($data['ordering_active_today'] ?? false),
            (bool) ($data['ordering_active_tomorrow'] ?? false),
            $data['ordering_inactive_title'],
            $data['ordering_inactive_message']
        );

        return back()->with('success', 'Ordering settings saved successfully.');
    }
}
