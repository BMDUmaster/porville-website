<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $coupons = $query->latest()->paginate(20);

        $stats = [
            'active'   => Coupon::where('is_active', true)->count(),
            'expired'  => Coupon::where('expires_at', '<', now())->count(),
            'total_redeemed' => Coupon::sum('used_count'),
        ];

        return view('dashboard.coupons.index', compact('coupons', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|unique:coupons,code|max:50',
            'type'             => 'required|in:flat,percent',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses'         => 'nullable|integer|min:1',
            'expires_at'       => 'required|date|after:today',
            'is_active'        => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Coupon::create($data);

        return back()->with('success', 'Coupon created successfully.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'             => 'required|in:flat,percent',
            'value'            => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_uses'         => 'nullable|integer|min:1',
            'expires_at'       => 'required|date',
            'is_active'        => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $coupon->update($data);

        return back()->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }
}
