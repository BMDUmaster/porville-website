<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Support\DeliveryChargeManager;
use App\Support\DeliverySlotManager;
use Illuminate\Http\Request;

class DeliverySlotController extends Controller
{
    public function index()
    {
        return view('dashboard.settings.delivery-slots', [
            'dates' => DeliverySlotManager::datesWithSlots(),
            'deliveryCharge' => DeliveryChargeManager::amount(),
            'defaultDeliveryCharge' => DeliveryChargeManager::defaultAmount(),
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'delivery_charge' => ['required', 'numeric', 'min:0'],
        ]);

        DeliveryChargeManager::updateAmount((float) $data['delivery_charge']);

        return back()->with('success', 'Delivery charge updated successfully.');
    }

    public function storeSlot(Request $request)
    {
        $data = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        DeliverySlotManager::addSlot($data['date'], $data['start_time'], $data['end_time']);

        return back()->with('success', 'Delivery slot added.');
    }

    public function destroySlot(int $slot)
    {
        DeliverySlotManager::deleteSlot($slot);

        return back()->with('success', 'Delivery slot removed.');
    }

    public function destroyDate(string $date)
    {
        DeliverySlotManager::deleteDate($date);

        return back()->with('success', 'All slots for that date removed.');
    }
}
