<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\DeliveryChargeManager;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer')->withCount('orders');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $searchId = ltrim($search, '#');

            if (ctype_digit($searchId) && User::where('role', 'customer')->where('id', (int) $searchId)->exists()) {
                $query->where('id', (int) $searchId);
            } else {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%');
                });
            }
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(20);

        $stats = [
            'total'   => User::where('role', 'customer')->count(),
            'active'  => User::where('role', 'customer')->where('status', 'active')->count(),
            'blocked' => User::where('role', 'customer')->where('status', 'blocked')->count(),
        ];

        return view('dashboard.users.index', [
            'users' => $users,
            'stats' => $stats,
            'globalDeliveryCharge' => DeliveryChargeManager::amount(),
        ]);
    }

    public function show(User $user)
    {
        $orders = $user->orders()
            ->with(['deliveryBoy', 'items.product'])
            ->latest()
            ->get();

        $user->setRelation('orders', $orders);

        $stats = [
            'total_orders' => $orders->count(),
            'delivered_orders' => $orders->where('status', 'delivered')->count(),
            'active_orders' => $orders->whereIn('status', ['pending', 'confirmed', 'processing', 'out_for_delivery'])->count(),
            'total_spent' => (float) $orders->sum('total'),
        ];

        $latestOrder = $orders->first();

        return view('dashboard.users.show', [
            'user' => $user,
            'stats' => $stats,
            'latestOrder' => $latestOrder,
            'globalDeliveryCharge' => DeliveryChargeManager::amount(),
        ]);
    }

    public function updateDeliveryCharge(Request $request, User $user)
    {
        abort_if($user->role !== 'customer', 404);

        if ($request->boolean('clear_delivery_charge')) {
            $user->update(['delivery_charge' => null]);

            return back()->with('success', 'Customer delivery charge reset to the global default.');
        }

        $data = $request->validate([
            'delivery_charge' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user->update([
            'delivery_charge' => $request->filled('delivery_charge')
                ? round((float) $data['delivery_charge'], 2)
                : null,
        ]);

        return back()->with(
            'success',
            $request->filled('delivery_charge')
                ? 'Customer delivery charge updated successfully.'
                : 'Customer delivery charge reset to the global default.'
        );
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'blocked' : 'active',
        ]);
        return back()->with('success', 'User status updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
