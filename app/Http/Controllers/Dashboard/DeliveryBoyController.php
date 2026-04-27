<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use Illuminate\Http\Request;

class DeliveryBoyController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryBoy::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('partner_name', 'like', '%' . $search . '%')
                  ->orWhere('phone_number', 'like', '%' . $search . '%')
                  ->orWhere('area', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deliveryBoys = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => DeliveryBoy::count(),
            'active' => DeliveryBoy::where('status', 'active')->count(),
            'inactive' => DeliveryBoy::where('status', 'inactive')->count(),
        ];

        return view('dashboard.delivery-boys.index', compact('deliveryBoys', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'partner_name' => 'required|string|max:150',
            'phone_number' => 'required|string|max:25',
            'area' => 'required|string|max:150',
            'status' => 'required|in:active,inactive',
            'last_assigned' => 'nullable|date',
        ]);

        DeliveryBoy::create($data);

        return back()->with('success', 'Delivery boy added successfully.');
    }

    public function toggleStatus(DeliveryBoy $deliveryBoy)
    {
        $deliveryBoy->update([
            'status' => $deliveryBoy->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Delivery boy status updated.');
    }

    public function destroy(DeliveryBoy $deliveryBoy)
    {
        $deliveryBoy->delete();

        return back()->with('success', 'Delivery boy deleted successfully.');
    }
}
