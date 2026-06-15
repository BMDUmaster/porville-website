<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /** GET /account/profile */
    public function index()
    {
        $user = Auth::guard('web_frontend')->user();

        $ordersQuery = $user->orders();
        $orders = $user->orders()->with('items.product')->latest()->take(4)->get();
        $userNotifications = Notification::query()
            ->forUser($user->id)
            ->latest()
            ->take(8)
            ->get();
        $unreadNotifications = Notification::query()
            ->where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->count();
        $totalNotifications = Notification::query()
            ->forUser($user->id)
            ->count();
        $latestAddressOrder = $user->orders()
            ->whereNotNull('shipping_address')
            ->latest()
            ->first();
        $latestAddress = $latestAddressOrder?->shipping_address ?? [];

        $totalSpent = (float) $ordersQuery->sum('total');
        $totalOrders = (clone $ordersQuery)->count();
        $deliveredOrders = (clone $ordersQuery)->where('status', 'delivered')->count();
        $activeOrders = (clone $ordersQuery)->whereIn('status', ['pending', 'confirmed', 'processing', 'out_for_delivery'])->count();
        $loyaltyPoints = (int) floor($totalSpent / 10);

        $profileChecks = collect([
            filled($user->name),
            filled($user->email),
            filled($user->phone),
            ! empty($latestAddress['address'] ?? null),
            ! in_array($user->status, ['blocked', 'inactive'], true),
        ]);

        $profileCompletion = (int) round(($profileChecks->filter()->count() / max(1, $profileChecks->count())) * 100);

        $stats = [
            'total_orders' => $totalOrders,
            'total_spent' => $totalSpent,
            'loyalty_points' => $loyaltyPoints,
            'member_since' => optional($user->created_at)->format('M y'),
            'delivered_orders' => $deliveredOrders,
            'active_orders' => $activeOrders,
            'profile_completion' => $profileCompletion,
        ];

        return view('frontend.profile', compact('user', 'orders', 'stats', 'latestAddress', 'userNotifications', 'unreadNotifications', 'totalNotifications'));
    }

    /** PUT /account/profile */
    public function update(Request $request)
    {
        $user = Auth::guard('web_frontend')->user();

        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => ['nullable', 'regex:/^(?:\d{10}|\d{12})$/'],
        ], [
            'phone.regex' => 'Phone number must be 10 or 12 digits.',
        ]);

        $data['phone'] = $data['phone'] ? preg_replace('/\D/', '', $data['phone']) : null;

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    /** PUT /account/password */
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('web_frontend')->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function markNotificationsRead()
    {
        $user = Auth::guard('web_frontend')->user();

        Notification::query()
            ->where('recipient_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Notifications marked as read.');
    }
}
