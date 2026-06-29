<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();
        $segment = $request->input('segment', 'my_coupons');

        if ($request->filled('search')) {
            $query->where(function ($searchQuery) use ($request) {
                $searchQuery->where('code', 'like', '%' . $request->search . '%')
                    ->orWhere('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('entry_type')) {
            $query->where('entry_type', $request->entry_type);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('expires_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('expires_at', '<=', $request->date_to);
        }

        match ($segment) {
            'flash_sales' => $query->where('entry_type', 'offer'),
            'bulk_discounts' => $query->where('entry_type', 'coupon')->where('type', 'flat'),
            'referral_offers' => $query->where('entry_type', 'coupon')->where('type', 'percent'),
            default => $query->where('entry_type', 'coupon'),
        };

        $coupons = $query->latest()->paginate(20);

        $thisMonth = now()->startOfMonth();
        $lastMonthStart = now()->copy()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = now()->copy()->subMonthNoOverflow()->endOfMonth();
        $currentMonthRedeemed = Coupon::whereMonth('created_at', now()->month)->sum('used_count');
        $lastMonthRedeemed = Coupon::whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum('used_count');
        $redeemedGrowth = $lastMonthRedeemed > 0
            ? round((($currentMonthRedeemed - $lastMonthRedeemed) / $lastMonthRedeemed) * 100)
            : ($currentMonthRedeemed > 0 ? 100 : 0);

        $stats = [
            'active'   => Coupon::activeEntries()->count(),
            'coupons'  => Coupon::coupons()->count(),
            'offers'   => Coupon::offers()->count(),
            'expired'  => Coupon::whereNotNull('expires_at')->where('expires_at', '<', now())->count(),
            'total_redeemed' => Coupon::sum('used_count'),
            'active_coupons' => Coupon::coupons()->where('is_active', true)->count(),
            'new_coupons_this_month' => Coupon::coupons()->where('created_at', '>=', $thisMonth)->count(),
            'flash_sales' => Coupon::offers()->count(),
            'active_flash_sales' => Coupon::offers()->where('is_active', true)->count(),
            'referrals' => Coupon::coupons()->where('type', 'percent')->count(),
            'referrals_this_week' => Coupon::coupons()->where('type', 'percent')->where('created_at', '>=', now()->startOfWeek())->count(),
            'redeemed_growth_text' => ($redeemedGrowth >= 0 ? '↑ ' : '↓ ') . abs($redeemedGrowth) . '% vs last month',
        ];

        return view('dashboard.coupons.index', compact('coupons', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['code'] = $this->normalizedCode($request);
        $data = $this->normalizeEntryPayload($data);
        Coupon::create($data);

        return back()->with('success', $data['entry_type'] === 'offer'
            ? 'Offer created successfully.'
            : 'Coupon created successfully.');
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $this->validatedData($request, $coupon);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['code'] = $this->normalizedCode($request, $coupon);
        $data = $this->normalizeEntryPayload($data);
        $coupon->update($data);

        return back()->with('success', $data['entry_type'] === 'offer'
            ? 'Offer updated.'
            : 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }

    private function validatedData(Request $request, ?Coupon $coupon = null): array
    {
        $entryType = $request->input('entry_type', 'coupon');
        $couponId = $coupon?->id;

        return $request->validate([
            'entry_type'       => ['required', Rule::in(['coupon', 'offer'])],
            'title'            => [Rule::requiredIf($entryType === 'offer'), 'nullable', 'string', 'max:120'],
            'description'      => [Rule::requiredIf($entryType === 'offer'), 'nullable', 'string', 'max:500'],
            'code'             => [Rule::requiredIf($entryType === 'coupon'), 'nullable', 'string', 'max:50', Rule::unique('coupons', 'code')->ignore($couponId)],
            'type'             => ['required', Rule::in(['flat', 'percent'])],
            'value'            => ['required', 'numeric', 'min:0.01', 'max:99999999.99'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_uses'         => ['nullable', 'integer', 'min:1'],
            'per_user_limit'   => ['nullable', 'integer', 'min:1'],
            'expires_at'       => [$entryType === 'coupon' && !$coupon ? 'required' : 'nullable', 'date'],
            'is_active'        => ['boolean'],
        ]);
    }

    private function normalizedCode(Request $request, ?Coupon $coupon = null): string
    {
        $requestedCode = strtoupper((string) $request->input('code', ''));
        $requestedCode = preg_replace('/[^A-Z0-9_-]/', '', $requestedCode);

        if ($request->input('entry_type', 'coupon') === 'coupon') {
            if ($requestedCode === '') {
                throw ValidationException::withMessages([
                    'code' => 'Coupon code must contain letters or numbers.',
                ]);
            }

            return $requestedCode;
        }

        if ($requestedCode !== '') {
            return $requestedCode;
        }

        if ($coupon && Str::startsWith($coupon->code, 'AUTO-OFFER-')) {
            return $coupon->code;
        }

        do {
            $generatedCode = 'AUTO-OFFER-' . strtoupper(Str::random(8));
        } while (Coupon::where('code', $generatedCode)
            ->when($coupon, fn ($query) => $query->where('id', '!=', $coupon->id))
            ->exists());

        return $generatedCode;
    }

    private function normalizeEntryPayload(array $data): array
    {
        if (($data['entry_type'] ?? 'coupon') !== 'offer') {
            $data['title'] = null;
            $data['description'] = null;
        }

        return $data;
    }
}
