@extends('layouts.dashboard')
@section('title', 'Coupons & Offers')
@section('page_title', 'Coupons & Offers')

@section('styles')
.coupon-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.coupon-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(99, 102, 241, 0.28);
    border-radius: 999px;
}
@endsection

@section('content')
@php
    $activeSegment = request('segment', 'my_coupons');

    $statCards = [
        [
            'label' => 'Active Coupons',
            'value' => number_format($stats['active_coupons']),
            'hint' => $stats['new_coupons_this_month'] . ' new this month',
            'color' => 'indigo',
            'icon' => 'fa-tags',
        ],
        [
            'label' => 'Flash Sales',
            'value' => number_format($stats['flash_sales']),
            'hint' => $stats['active_flash_sales'] . ' active now',
            'color' => 'amber',
            'icon' => 'fa-bolt',
        ],
        [
            'label' => 'Referrals',
            'value' => number_format($stats['referrals']),
            'hint' => $stats['referrals_this_week'] . ' this week',
            'color' => 'violet',
            'icon' => 'fa-gift',
        ],
        [
            'label' => 'Total Redeemed',
            'value' => number_format($stats['total_redeemed']),
            'hint' => $stats['redeemed_growth_text'],
            'color' => 'blue',
            'icon' => 'fa-chart-simple',
        ],
    ];

    $quickActions = [
        ['title' => 'New Coupon', 'subtitle' => 'Create product coupon', 'icon' => 'fa-tags', 'color' => 'indigo', 'payload' => null],
        ['title' => 'Flash Sale', 'subtitle' => 'Limited time offer', 'icon' => 'fa-bolt', 'color' => 'amber', 'payload' => ['entry_type' => 'offer']],
        ['title' => 'Bulk Discount', 'subtitle' => 'Volume based pricing', 'icon' => 'fa-cubes-stacked', 'color' => 'emerald', 'payload' => ['entry_type' => 'coupon', 'type' => 'flat']],
        ['title' => 'Referral Offer', 'subtitle' => 'Refer & earn', 'icon' => 'fa-user-plus', 'color' => 'violet', 'payload' => ['entry_type' => 'coupon', 'type' => 'percent']],
    ];

    $tabs = [
        'my_coupons' => ['label' => 'My Coupons', 'icon' => 'fa-tags'],
        'flash_sales' => ['label' => 'Flash Sales', 'icon' => 'fa-bolt'],
        'bulk_discounts' => ['label' => 'Bulk Discounts', 'icon' => 'fa-cubes-stacked'],
        'referral_offers' => ['label' => 'Referral Offers', 'icon' => 'fa-user-plus'],
    ];

    $titleBySegment = [
        'my_coupons' => 'My Active Coupons',
        'flash_sales' => 'Flash Sale Entries',
        'bulk_discounts' => 'Bulk Discount Entries',
        'referral_offers' => 'Referral Offer Entries',
    ];

    $rangeBase = collect(request()->except('segment', 'page'))->filter(fn ($value) => $value !== null && $value !== '');
    $tableTitle = $titleBySegment[$activeSegment] ?? 'My Active Coupons';
@endphp

<div class="space-y-6 p-4 md:p-8">
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($statCards as $card)
            <div class="rounded-[22px] border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-{{ $card['color'] }}-100 text-{{ $card['color'] }}-600">
                        <i class="fa-solid {{ $card['icon'] }} text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">{{ $card['label'] }}</p>
                        <h2 class="mt-1 text-3xl font-black leading-none text-slate-900 sm:text-[38px]">{{ $card['value'] }}</h2>
                        <p class="mt-1 text-sm font-medium text-{{ $card['color'] }}-600">{{ $card['hint'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach($quickActions as $action)
            <button type="button"
                    onclick='openCouponModal(@json($action["payload"]))'
                    class="flex items-center gap-4 rounded-[20px] border border-slate-200 bg-white px-5 py-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md">
                <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-{{ $action['color'] }}-100 text-{{ $action['color'] }}-600">
                    <i class="fa-solid {{ $action['icon'] }} text-lg"></i>
                </span>
                <span class="min-w-0">
                    <span class="block text-xl font-black leading-tight text-slate-900 sm:text-2xl">{{ $action['title'] }}</span>
                    <span class="mt-1 block text-sm text-slate-400">{{ $action['subtitle'] }}</span>
                </span>
            </button>
        @endforeach
    </div>

    <div class="rounded-[24px] border border-slate-200 bg-white p-4 shadow-sm">
        <form method="GET" class="grid gap-3 md:grid-cols-2 xl:grid-cols-[1.3fr_1fr_1fr_1.1fr_1.1fr_1.05fr_1.15fr]">
            <input type="hidden" name="segment" value="{{ $activeSegment }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by code/name"
                   class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500">
            <select name="entry_type" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500">
                <option value="">All Types</option>
                <option value="coupon" {{ request('entry_type') === 'coupon' ? 'selected' : '' }}>Coupons</option>
                <option value="offer" {{ request('entry_type') === 'offer' ? 'selected' : '' }}>Offers</option>
            </select>
            <select name="status" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-indigo-500">
            <a href="{{ route('dashboard.coupons') }}"
               class="inline-flex items-center justify-center rounded-2xl bg-slate-100 px-4 py-3 text-sm font-bold text-slate-600 transition hover:bg-slate-200">
                Reset Filters
            </a>
            <button type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-indigo-700">
                Apply Filters
            </button>
        </form>
    </div>

    <div class="overflow-x-auto pb-1 coupon-scrollbar">
        <div class="flex w-max min-w-full gap-3">
        @foreach($tabs as $segment => $tab)
            @php
                $tabUrl = route('dashboard.coupons', $rangeBase->merge(['segment' => $segment])->all());
                $isActive = $activeSegment === $segment;
            @endphp
            <a href="{{ $tabUrl }}"
               class="inline-flex items-center gap-2 rounded-2xl border px-5 py-3 text-sm font-bold transition {{ $isActive ? 'border-indigo-600 bg-indigo-600 text-white shadow-md' : 'border-slate-200 bg-white text-slate-600 hover:border-indigo-200 hover:text-indigo-600' }}">
                <i class="fa-solid {{ $tab['icon'] }}"></i>
                {{ $tab['label'] }}
            </a>
        @endforeach
        </div>
    </div>

    <div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-200 px-5 py-5 md:flex-row md:items-center md:justify-between">
            <h2 class="text-2xl font-black tracking-tight text-slate-900 md:text-[28px]">{{ $tableTitle }}</h2>
            <button type="button" onclick="openCouponModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-indigo-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-indigo-700">
                <i class="fa-solid fa-circle-plus"></i>
                Create Coupon
            </button>
        </div>

        <div class="overflow-x-auto coupon-scrollbar">
            <table class="w-full min-w-[1220px] text-left">
                <thead class="bg-slate-50/80 text-sm font-bold text-slate-500">
                    <tr>
                        <th class="px-6 py-5">Code</th>
                        <th class="px-6 py-5">Discount</th>
                        <th class="px-6 py-5">Min Order</th>
                        <th class="px-6 py-5">Valid From</th>
                        <th class="px-6 py-5">Valid Till</th>
                        <th class="px-6 py-5">Usage</th>
                        <th class="px-6 py-5">Status</th>
                        <th class="px-6 py-5 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-[15px] text-slate-700">
                    @forelse($coupons as $coupon)
                        @php
                            $payload = [
                                'id' => $coupon->id,
                                'entry_type' => $coupon->entry_type ?? 'coupon',
                                'title' => $coupon->title,
                                'description' => $coupon->description,
                                'product_id' => $coupon->product_id,
                                'code' => $coupon->code,
                                'type' => $coupon->type,
                                'value' => $coupon->value,
                                'min_order_amount' => $coupon->min_order_amount,
                                'max_uses' => $coupon->max_uses,
                                'per_user_limit' => $coupon->per_user_limit,
                                'starts_at' => optional($coupon->starts_at)->format('Y-m-d'),
                                'expires_at' => optional($coupon->expires_at)->format('Y-m-d\TH:i'),
                                'is_active' => $coupon->is_active ? 1 : 0,
                            ];

                            $displayCode = ($coupon->entry_type ?? 'coupon') === 'offer'
                                ? ($coupon->title ?: 'Special Offer')
                                : $coupon->code;

                            $displaySubtext = ($coupon->entry_type ?? 'coupon') === 'offer'
                                ? (($coupon->description ?: 'No description added.') . ' | Applies to: ' . ($coupon->product?->name ?: 'All Products'))
                                : ('Code: ' . $coupon->code);

                            $maxUses = (int) ($coupon->max_uses ?? 0);
                            $usagePercentage = $maxUses > 0 ? min(100, (int) round(($coupon->used_count / max(1, $maxUses)) * 100)) : 100;
                            $usageLabel = $maxUses > 0 ? $coupon->used_count . '/' . $maxUses : 'Unlimited';
                            $validFrom = optional($coupon->created_at)->format('d M Y');
                            $validTill = $coupon->expires_at?->format('d M Y') ?? '-';
                        @endphp
                        <tr class="transition hover:bg-slate-50/60">
                            <td class="px-6 py-5 align-top">
                                <div class="max-w-[220px]">
                                    <p class="break-all text-[15px] font-black text-indigo-600">{{ $displayCode }}</p>
                                    <p class="mt-1 max-h-14 overflow-y-auto break-all pr-2 text-xs leading-5 text-slate-400 coupon-scrollbar">{{ $displaySubtext }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-5 font-semibold text-slate-900">
                                @if(($coupon->entry_type ?? 'coupon') === 'offer')
                                    {{ $coupon->type === 'percent'
                                        ? rtrim(rtrim(number_format((float) $coupon->value, 2, '.', ''), '0'), '.') . '% OFF'
                                        : 'Rs' . number_format((float) $coupon->value, 0) . ' OFF' }}
                                @elseif($coupon->type === 'percent')
                                    {{ rtrim(rtrim(number_format((float) $coupon->value, 2, '.', ''), '0'), '.') }}% OFF
                                @else
                                    &#8377;{{ number_format((float) $coupon->value, 0) }} OFF
                                @endif
                            </td>
                            <td class="px-6 py-5 font-medium text-slate-900">
                                {{ $coupon->min_order_amount ? 'Rs' . number_format((float) $coupon->min_order_amount, 0) : '-' }}
                            </td>
                            <td class="px-6 py-5 text-slate-900">{{ $validFrom }}</td>
                            <td class="px-6 py-5 text-slate-900">{{ $validTill }}</td>
                            <td class="px-6 py-5">
                                @if($maxUses > 0)
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-slate-900">{{ $usageLabel }}</span>
                                        <div class="h-1.5 w-16 overflow-hidden rounded-full bg-slate-200">
                                            <div class="h-full rounded-full bg-emerald-500" style="width: {{ $usagePercentage }}%"></div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-400">Unlimited</span>
                                @endif
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex rounded-full px-3 py-1 text-sm font-medium {{ $coupon->is_active ? 'text-slate-900' : 'text-slate-400' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center justify-center gap-4 text-lg">
                                    <button type="button" onclick='openCouponModal(@json($payload))'
                                            class="text-indigo-500 transition hover:text-indigo-700">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button type="button"
                                            onclick='copyCouponCode(@json($coupon->code))'
                                            class="text-blue-500 transition hover:text-blue-700">
                                        <i class="fa-regular fa-copy"></i>
                                    </button>
                                    <form method="POST" action="{{ route('dashboard.coupons.destroy', $coupon) }}"
                                          onsubmit="return confirm('Delete this entry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 transition hover:text-red-700">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-sm font-semibold text-slate-400">No coupons or offers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <p class="text-sm text-slate-500">
            Page {{ $coupons->currentPage() }} of {{ max(1, $coupons->lastPage()) }} —
            Showing <span class="font-bold text-slate-700">{{ $coupons->firstItem() ?? 0 }}-{{ $coupons->lastItem() ?? 0 }}</span>
            of <span class="font-bold text-slate-700">{{ $coupons->total() }}</span> entries
        </p>
        <div>{{ $coupons->withQueryString()->links() }}</div>
    </div>
</div>

<div id="couponModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-slate-950/60 p-4 backdrop-blur-sm">
    <div class="max-h-[92vh] w-full max-w-3xl overflow-y-auto rounded-[28px] bg-white shadow-2xl coupon-scrollbar">
        <div id="couponModalHeader" class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-indigo-50 to-white px-6 py-5">
            <div class="flex items-center gap-3">
                <span id="couponModalIcon" class="flex h-11 w-11 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200"><i class="fa-solid fa-tags"></i></span>
                <div>
                    <h2 id="couponModalTitle" class="text-xl font-black text-slate-900">Create Coupon</h2>
                    <p id="couponModalSubtitle" class="mt-0.5 text-xs font-semibold text-slate-400">Create a new customer discount</p>
                </div>
            </div>
            <button type="button" onclick="closeCouponModal()" class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-2xl leading-none text-slate-400 shadow-sm transition hover:text-slate-700">&times;</button>
        </div>

        <form id="couponForm" method="POST" action="{{ route('dashboard.coupons.store') }}" class="space-y-5 p-6">
            @csrf
            <input type="hidden" name="_method" id="couponMethod" value="POST">

            <div>
                <label class="mb-1 block text-sm font-bold text-slate-700">Entry Type</label>
                <select name="entry_type" id="couponEntryType" onchange="toggleCouponFields()"
                        class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                    <option value="coupon">Coupon</option>
                    <option value="offer">Offer</option>
                </select>
            </div>

            <div id="offerFields" class="grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Offer Title</label>
                    <input type="text" name="title" id="couponTitle" maxlength="120"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Description</label>
                    <textarea name="description" id="couponDescription" rows="3" maxlength="500"
                              class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500"></textarea>
                </div>
                <div class="md:col-span-2 rounded-2xl border border-amber-100 bg-amber-50/60 p-4">
                    <label class="mb-2 block text-sm font-black text-slate-700">Related Product</label>
                    <select name="product_id" id="couponProductId" class="w-full rounded-xl border border-amber-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none focus:border-amber-500">
                        <option value="">All Products</option>
                        @foreach($offerProducts as $offerProduct)
                            <option value="{{ $offerProduct->id }}">{{ $offerProduct->name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-2 text-xs font-medium text-amber-700"><i class="fa-solid fa-circle-info mr-1"></i>Select one product, or keep “All Products” to show this offer everywhere.</p>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-bold text-slate-700">Start Date</label>
                    <input type="date" name="starts_at" id="couponStartsAt" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="mb-1 block text-sm font-bold text-slate-700">End Date</label>
                    <input type="date" id="offerExpiresAt" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-amber-500">
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div id="couponCodeWrap">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Code</label>
                    <input type="text" name="code" id="couponCode" maxlength="50"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm uppercase outline-none focus:border-indigo-500">
                    <p class="mt-1 text-xs text-slate-400">Offer ke liye blank chhodoge to auto code ban jayega.</p>
                </div>
                <div id="couponTypeWrap">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Discount Type</label>
                    <select name="type" id="couponType"
                            class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                        <option value="percent">Percentage (%)</option>
                        <option value="flat">Flat (Rs)</option>
                    </select>
                </div>
                <div id="couponValueWrap">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Value</label>
                    <input type="number" name="value" id="couponValue" min="0" step="0.01"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                </div>
                <div id="couponMinOrderWrap">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Min Order Amount</label>
                    <input type="number" name="min_order_amount" id="couponMinOrder" min="0" step="0.01"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                </div>
                <div id="couponMaxUsesWrap">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Total Uses Limit</label>
                    <input type="number" name="max_uses" id="couponMaxUses" min="1"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                    <p class="mt-1 text-xs text-slate-400">Blank means unlimited total redemptions.</p>
                </div>
                <div id="couponPerUserWrap">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Uses Per User Limit</label>
                    <input type="number" name="per_user_limit" id="couponPerUserLimit" min="1"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                    <p class="mt-1 text-xs text-slate-400">Blank means unlimited per customer.</p>
                </div>
                <div id="couponExpiresWrap">
                    <label class="mb-1 block text-sm font-bold text-slate-700">Expires At</label>
                    <input type="datetime-local" name="expires_at" id="couponExpiresAt"
                           class="w-full rounded-xl border px-4 py-2.5 text-sm outline-none focus:border-indigo-500">
                </div>
            </div>

            <label class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-700">
                <span><i class="fa-solid fa-toggle-on mr-2 text-emerald-500"></i>Status</span>
                <span class="flex items-center gap-2"><input type="checkbox" name="is_active" id="couponIsActive" value="1" class="h-5 w-5 accent-indigo-600" checked> Active</span>
            </label>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                <button id="couponSubmitButton" type="submit" class="flex-1 rounded-xl bg-indigo-600 py-3 text-sm font-bold text-white transition hover:bg-indigo-700">Save Entry</button>
                <button type="button" onclick="closeCouponModal()" class="flex-1 rounded-xl bg-slate-100 py-3 text-sm font-bold text-slate-600">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const couponStoreUrl = @json(route('dashboard.coupons.store'));
const couponUpdateBaseUrl = @json(url('/coupons'));

function openCouponModal(entry = null) {
    const modal = document.getElementById('couponModal');
    const form = document.getElementById('couponForm');

    document.getElementById('couponMethod').value = entry?.id ? 'PUT' : 'POST';
    form.action = entry?.id ? `${couponUpdateBaseUrl}/${entry.id}` : couponStoreUrl;

    document.getElementById('couponEntryType').value = entry?.entry_type ?? 'coupon';
    document.getElementById('couponTitle').value = entry?.title ?? '';
    document.getElementById('couponDescription').value = entry?.description ?? '';
    document.getElementById('couponProductId').value = entry?.product_id ?? '';
    document.getElementById('couponCode').value = entry?.code ?? '';
    document.getElementById('couponType').value = entry?.type ?? 'percent';
    document.getElementById('couponValue').value = entry?.value ?? '';
    document.getElementById('couponMinOrder').value = entry?.min_order_amount ?? '';
    document.getElementById('couponMaxUses').value = entry?.max_uses ?? '';
    document.getElementById('couponPerUserLimit').value = entry?.per_user_limit ?? '';
    document.getElementById('couponStartsAt').value = entry?.starts_at ?? '';
    document.getElementById('couponExpiresAt').value = entry?.expires_at ?? '';
    document.getElementById('offerExpiresAt').value = (entry?.expires_at ?? '').slice(0, 10);
    document.getElementById('couponIsActive').checked = Number(entry?.is_active ?? 1) === 1;

    toggleCouponFields();
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeCouponModal() {
    const modal = document.getElementById('couponModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function toggleCouponFields() {
    const entryType = document.getElementById('couponEntryType').value;
    const isCoupon = entryType === 'coupon';

    const offerFields = document.getElementById('offerFields');
    const couponOnlyIds = ['couponCodeWrap', 'couponExpiresWrap'];
    const sharedLimitIds = ['couponMinOrderWrap', 'couponMaxUsesWrap', 'couponPerUserWrap'];
    const couponExpiresAt = document.getElementById('couponExpiresAt');
    const offerExpiresAt = document.getElementById('offerExpiresAt');

    offerFields.classList.toggle('hidden', isCoupon);
    couponOnlyIds.forEach(id => document.getElementById(id).classList.toggle('hidden', !isCoupon));
    sharedLimitIds.forEach(id => document.getElementById(id).classList.remove('hidden'));
    document.getElementById('couponTitle').required = !isCoupon;
    document.getElementById('couponDescription').required = !isCoupon;
    document.getElementById('couponStartsAt').required = !isCoupon;
    document.getElementById('couponStartsAt').disabled = isCoupon;
    document.getElementById('couponProductId').disabled = isCoupon;

    couponExpiresAt.disabled = !isCoupon;
    couponExpiresAt.name = isCoupon ? 'expires_at' : '';
    offerExpiresAt.disabled = isCoupon;
    offerExpiresAt.required = !isCoupon;
    offerExpiresAt.name = isCoupon ? '' : 'expires_at';

    document.getElementById('couponType').required = true;
    document.getElementById('couponValue').required = true;
    document.getElementById('couponCode').required = isCoupon;

    const title = document.getElementById('couponModalTitle');
    const subtitle = document.getElementById('couponModalSubtitle');
    const icon = document.getElementById('couponModalIcon');
    const submit = document.getElementById('couponSubmitButton');
    const editing = document.getElementById('couponMethod').value === 'PUT';

    title.textContent = editing ? (isCoupon ? 'Edit Coupon' : 'Edit Offer') : (isCoupon ? 'Add New Coupon' : 'Add New Offer');
    subtitle.textContent = isCoupon ? 'Create a customer coupon code' : 'Choose where and when this offer will appear';
    submit.textContent = editing ? (isCoupon ? 'Update Coupon' : 'Update Offer') : (isCoupon ? 'Save Coupon' : 'Save Offer');
    submit.className = `flex-1 rounded-xl py-3 text-sm font-bold text-white transition ${isCoupon ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-amber-500 hover:bg-amber-600'}`;
    icon.className = `flex h-11 w-11 items-center justify-center rounded-2xl text-white shadow-lg ${isCoupon ? 'bg-indigo-600 shadow-indigo-200' : 'bg-amber-500 shadow-amber-200'}`;
    icon.innerHTML = `<i class="fa-solid ${isCoupon ? 'fa-tags' : 'fa-bolt'}"></i>`;
}

function copyCouponCode(code) {
    if (!code) {
        return;
    }

    navigator.clipboard?.writeText(code);
}
</script>
@endsection
