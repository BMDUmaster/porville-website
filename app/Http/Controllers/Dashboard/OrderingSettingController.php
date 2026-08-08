<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\OrderingManager;
use Illuminate\Http\Request;

class OrderingSettingController extends Controller
{
    public function index()
    {
        return view('dashboard.settings.ordering', [
            'isActive' => OrderingManager::isActive(),
            'title'    => OrderingManager::inactiveTitle(),
            'message'  => OrderingManager::inactiveMessage(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'ordering_active'           => 'nullable|boolean',
            'ordering_inactive_title'   => 'required|string|max:120',
            'ordering_inactive_message' => 'required|string|max:500',
        ]);

        OrderingManager::update(
            (bool) ($data['ordering_active'] ?? false),
            $data['ordering_inactive_title'],
            $data['ordering_inactive_message']
        );

        return back()->with('success', 'Ordering settings saved successfully.');
    }
}
