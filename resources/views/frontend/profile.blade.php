@extends('frontend.layouts.app')
@section('title', 'My Profile')

@section('styles')
<style>
.profile-shell {
    background:
        radial-gradient(circle at top left, rgba(34, 197, 94, 0.14), transparent 28%),
        radial-gradient(circle at top right, rgba(37, 99, 235, 0.14), transparent 32%),
        linear-gradient(180deg, #f6f8fb 0%, #eef3f8 100%);
}
.profile-glass {
    backdrop-filter: blur(18px);
}
.profile-soft-shadow {
    box-shadow: 0 18px 48px rgba(15, 23, 42, 0.08);
}
.custom-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 transparent;
}
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
@media (max-width: 640px) {
    .profile-shell .profile-card { padding: 14px; border-radius: 20px; }
    .profile-shell .profile-info-row { padding: 12px; }
}
</style>
@endsection

@section('content')
@php
    $memberSince = $stats['member_since'] ?: optional($user->created_at)->format('M Y');
    $profileCompletion = max(12, min(100, $stats['profile_completion']));
    $remainingPoints = max(0, 1000 - $stats['loyalty_points']);
@endphp

<div class="profile-shell min-h-screen">
    <div class="mx-auto w-full max-w-[1280px] px-3 py-4 sm:px-4 sm:py-6 md:px-6 md:py-8">
        <div class="overflow-hidden rounded-[30px] border border-white/60 bg-gradient-to-r from-[#257a2f] via-[#257b57] to-[#2567b8] text-white profile-soft-shadow">
            <div class="flex flex-col gap-6 px-5 py-6 md:px-8 md:py-7 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex items-start gap-4">
                    <div class="relative flex h-20 w-20 items-center justify-center rounded-[24px] border border-white/20 bg-white/12 text-3xl font-black profile-glass">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                        <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full border-2 border-white bg-emerald-400 text-[11px] text-emerald-950">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>
                    <div class="pt-1">
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-white/70">FarmSea Account</p>
                        <h1 class="nunito mt-1 text-2xl font-extrabold md:text-[30px]">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm text-white/78">
                            {{ in_array($user->status, ['blocked', 'inactive'], true) ? 'Account Restricted' : 'Verified Customer' }}
                        </p>
                        <div class="mt-3 flex flex-wrap gap-2 text-[11px] font-semibold">
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/18 bg-white/10 px-3 py-1.5 profile-glass">
                                <i class="fa-solid fa-envelope text-[10px]"></i>
                                {{ $user->email }}
                            </span>
                            @if($user->phone)
                                <span class="inline-flex items-center gap-2 rounded-full border border-white/18 bg-white/10 px-3 py-1.5 profile-glass">
                                    <i class="fa-solid fa-phone text-[10px]"></i>
                                    {{ $user->phone }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 lg:justify-end">
                    <a href="#profile-edit" class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-xs font-bold text-slate-800 shadow-sm transition hover:bg-slate-100">
                        <i class="fa-solid fa-pen-to-square text-emerald-600"></i>
                        Edit Profile
                    </a>
                    <a href="#security-panel" class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold text-white transition hover:bg-white/18">
                        <i class="fa-solid fa-lock"></i>
                        Password
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3 md:grid-cols-4">
            <div class="rounded-[18px] border border-slate-200/80 bg-white px-4 py-4 text-center profile-soft-shadow">
                <p class="nunito text-xl font-extrabold text-emerald-700 md:text-2xl">{{ $stats['total_orders'] }}</p>
                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Orders</p>
            </div>
            <div class="rounded-[18px] border border-slate-200/80 bg-white px-4 py-4 text-center profile-soft-shadow">
                <p class="nunito text-xl font-extrabold text-emerald-700 md:text-2xl">&#8377;{{ number_format($stats['total_spent'], 0) }}</p>
                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Total Spent</p>
            </div>
            <div class="rounded-[18px] border border-slate-200/80 bg-white px-4 py-4 text-center profile-soft-shadow">
                <p class="nunito text-xl font-extrabold text-emerald-700 md:text-2xl">{{ $stats['loyalty_points'] }}</p>
                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Loyalty Pts</p>
            </div>
            <div class="rounded-[18px] border border-slate-200/80 bg-white px-4 py-4 text-center profile-soft-shadow">
                <p class="nunito text-xl font-extrabold text-emerald-700 md:text-2xl">{{ $memberSince }}</p>
                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Member Since</p>
            </div>
        </div>

        <div class="mt-5 grid min-w-0 gap-5 lg:grid-cols-[1.95fr_0.98fr]">
            <div class="min-w-0 space-y-5">
                <div class="profile-card min-w-0 overflow-hidden rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <div class="mb-4 flex min-w-0 items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h2 class="text-sm font-extrabold text-slate-900">
                                <i class="fa-solid fa-circle-user mr-2 text-blue-600"></i>
                                Personal Information
                            </h2>
                            <p class="mt-1 text-xs text-slate-400">Your main account details in one place.</p>
                        </div>
                        <a href="#profile-edit" class="flex-shrink-0 text-[11px] font-bold uppercase tracking-[0.18em] text-blue-600">Edit</a>
                    </div>

                    <div class="space-y-3">
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Full Name</p>
                            <div class="mt-1 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                <i class="fa-solid fa-pen text-xs text-slate-400"></i>
                            </div>
                        </div>
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Email Address</p>
                            <div class="mt-1 flex min-w-0 items-center justify-between gap-2">
                                <p class="min-w-0 break-all text-sm font-semibold text-slate-900">{{ $user->email }}</p>
                                <span class="flex-shrink-0 rounded-full bg-emerald-50 px-2 py-1 text-[9px] font-bold uppercase tracking-[0.12em] text-emerald-600 sm:px-2.5 sm:text-[10px] sm:tracking-[0.18em]">
                                    {{ $user->email_verified_at ? 'Verified' : 'Primary' }}
                                </span>
                            </div>
                        </div>
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Phone Number</p>
                            <div class="mt-1 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900">{{ $user->phone ?: 'Add your phone number' }}</p>
                                <i class="fa-solid fa-phone text-xs text-slate-400"></i>
                            </div>
                        </div>
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Date of Birth</p>
                            <div class="mt-1 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900">Add this later</p>
                                <i class="fa-solid fa-cake-candles text-xs text-slate-400"></i>
                            </div>
                        </div>
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Gender</p>
                            <div class="mt-1 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900">Not set</p>
                                <i class="fa-solid fa-user text-xs text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">
                                <i class="fa-solid fa-location-dot mr-2 text-emerald-600"></i>
                                Delivery Address
                            </h2>
                            <p class="mt-1 text-xs text-slate-400">Latest delivery location linked to your orders.</p>
                        </div>
                        <a href="{{ route('frontend.orders') }}" class="text-[11px] font-bold uppercase tracking-[0.18em] text-blue-600">View Orders</a>
                    </div>

                    <div class="rounded-[20px] border border-emerald-100 bg-emerald-50/60 px-4 py-4">
                        @if(!empty($latestAddress))
                            <div class="grid gap-2 text-sm text-slate-700">
                                @if(!empty($latestAddress['name']))
                                    <p class="font-bold text-slate-900">{{ $latestAddress['name'] }}</p>
                                @endif
                                @if(!empty($latestAddress['address']))
                                    <p><i class="fa-solid fa-road mr-2 text-emerald-600"></i>{{ $latestAddress['address'] }}</p>
                                @endif
                                <p>
                                    <i class="fa-solid fa-city mr-2 text-emerald-600"></i>
                                    {{ collect([$latestAddress['city'] ?? null, $latestAddress['state'] ?? null, $latestAddress['pincode'] ?? null])->filter()->implode(', ') ?: 'Address details available in latest order' }}
                                </p>
                                @if(!empty($latestAddress['phone']))
                                    <p><i class="fa-solid fa-phone mr-2 text-emerald-600"></i>{{ $latestAddress['phone'] }}</p>
                                @endif
                            </div>
                            <div class="mt-4 inline-flex rounded-full bg-white px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-emerald-700 shadow-sm">
                                Primary Address
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-emerald-200 bg-white/70 px-4 py-5 text-sm text-slate-500">
                                No delivery address yet. Place your first order and your saved shipping details will appear here.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">
                                <i class="fa-solid fa-bag-shopping mr-2 text-amber-500"></i>
                                Recent Orders
                            </h2>
                            <p class="mt-1 text-xs text-slate-400">Your latest purchases and order activity.</p>
                        </div>
                        <a href="{{ route('frontend.orders') }}" class="text-[11px] font-bold uppercase tracking-[0.18em] text-blue-600">View All</a>
                    </div>

                    @if($orders->count())
                        <div class="space-y-3">
                            @foreach($orders as $order)
                                @php
                                    $firstItem = $order->items->first();
                                    $firstProduct = $firstItem?->product;
                                    $orderImage = $firstProduct && !empty($firstProduct->images[0] ?? null) ? asset('storage/' . $firstProduct->images[0]) : null;
                                @endphp
                                <a href="{{ route('frontend.order.show', $order->id) }}" class="flex items-center gap-3 rounded-[20px] border border-slate-200 bg-white px-3 py-3 transition hover:border-blue-200 hover:bg-blue-50/30">
                                    <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl bg-slate-100">
                                        @if($orderImage)
                                            <img src="{{ $orderImage }}" alt="{{ $firstProduct->name ?? 'Product' }}" class="h-full w-full object-cover">
                                        @else
                                            <i class="fa-solid fa-drumstick-bite text-lg text-slate-400"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <p class="truncate text-sm font-bold text-slate-900">
                                                {{ $firstProduct->name ?? ($order->order_number ?? '#'.$order->id) }}
                                            </p>
                                            <p class="text-sm font-extrabold text-slate-900">&#8377;{{ number_format($order->total, 0) }}</p>
                                        </div>
                                        <p class="mt-1 text-xs text-slate-400">
                                            {{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} item(s)
                                        </p>
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.16em] {{ $order->status_badge_class }}">
                                                {{ $order->status_label }}
                                            </span>
                                            <span class="text-[11px] font-semibold text-slate-500">{{ $order->order_number ?? '#'.$order->id }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-[20px] border border-dashed border-slate-200 bg-slate-50 px-5 py-8 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-white shadow-sm">
                                <i class="fa-solid fa-basket-shopping text-lg text-slate-400"></i>
                            </div>
                            <h3 class="mt-4 text-sm font-bold text-slate-800">No orders yet</h3>
                            <p class="mt-1 text-sm text-slate-500">Start shopping and your recent orders will appear here.</p>
                            <a href="{{ route('frontend.products') }}" class="mt-4 inline-flex items-center gap-2 rounded-full bg-blue-700 px-4 py-2 text-xs font-bold text-white transition hover:bg-blue-800">
                                Browse Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="min-w-0 space-y-5">
                <div class="overflow-hidden rounded-[24px] border border-slate-200/80 bg-white profile-soft-shadow">
                    <div class="bg-gradient-to-r from-[#257a2f] to-[#2567b8] px-5 py-5 text-white">
                        <p class="nunito text-4xl font-extrabold leading-none">{{ $stats['loyalty_points'] }}</p>
                        <p class="mt-2 text-sm font-semibold text-white/85">Current loyalty points</p>
                        <div class="mt-5 h-2 overflow-hidden rounded-full bg-white/20">
                            <div class="h-full rounded-full bg-white" style="width: {{ $profileCompletion }}%"></div>
                        </div>
                        <p class="mt-2 text-[11px] text-white/75">
                            {{ $remainingPoints > 0 ? $remainingPoints . ' more points to reach the next badge tier' : 'Top tier unlocked for your account' }}
                        </p>
                    </div>
                </div>

                <div id="notifications-panel" class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <div class="mb-4 flex items-start justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">
                                <i class="fa-regular fa-bell mr-2 text-blue-600"></i>
                                Notifications
                            </h2>
                            <p class="mt-1 text-xs text-slate-400">
                                {{ $totalNotifications ?? ($userNotifications ?? collect())->count() }} message(s) from FarmSea team.
                            </p>
                        </div>
                        <div class="flex flex-col items-end gap-2">
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.12em] text-slate-600">
                                {{ $totalNotifications ?? ($userNotifications ?? collect())->count() }} Total
                            </span>
                            @if(($unreadNotifications ?? 0) > 0)
                                <span class="rounded-full bg-red-500 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.12em] text-white">
                                    {{ $unreadNotifications }} New
                                </span>
                                <form method="POST" action="{{ route('frontend.notifications.read') }}">
                                    @csrf
                                    <button type="submit" class="text-[10px] font-black uppercase tracking-[0.14em] text-blue-600 hover:underline">
                                        Mark all read
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @if(($userNotifications ?? collect())->count())
                        <div class="max-h-[302px] space-y-3 overflow-y-auto overscroll-contain pr-2 custom-scrollbar">
                            @foreach($userNotifications as $notification)
                                <div class="flex min-h-[145px] flex-col rounded-2xl border {{ $notification->read_at ? 'border-slate-200 bg-slate-50/70' : 'border-blue-100 bg-blue-50/70' }} px-4 py-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <p class="line-clamp-2 text-sm font-bold text-slate-900">{{ $notification->subject }}</p>
                                        @if(! $notification->read_at && $notification->recipient_id)
                                            <span class="rounded-full bg-blue-600 px-2 py-0.5 text-[9px] font-black uppercase tracking-[0.12em] text-white">New</span>
                                        @endif
                                    </div>
                                    <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600">{{ $notification->message }}</p>
                                    <p class="mt-auto pt-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">
                                        {{ $notification->created_at->format('d M Y, h:i A') }}
                                    </p>
                                    <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-200/80 pt-3">
                                        <button
                                            type="button"
                                            class="notification-view-btn rounded-lg bg-slate-900 px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.12em] text-white transition hover:bg-slate-700"
                                            data-subject="{{ $notification->subject }}"
                                            data-message="{{ $notification->message }}"
                                            data-date="{{ $notification->created_at->format('d M Y, h:i A') }}"
                                        >
                                            View
                                        </button>
                                        @if($notification->recipient_id === $user->id)
                                            @if(! $notification->read_at)
                                                <form method="POST" action="{{ route('frontend.notifications.mark-read', $notification) }}">
                                                    @csrf
                                                    <button type="submit" class="rounded-lg border border-blue-200 bg-white px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.12em] text-blue-700 transition hover:bg-blue-50">
                                                        Mark as Read
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('frontend.notifications.delete', $notification) }}" onsubmit="return confirm('Delete this notification?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.12em] text-red-600 transition hover:bg-red-50">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center">
                            <i class="fa-regular fa-bell text-2xl text-slate-300"></i>
                            <p class="mt-3 text-sm font-semibold text-slate-500">No notifications yet.</p>
                        </div>
                    @endif
                </div>

                <div id="security-panel" class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-shield-heart mr-2 text-red-500"></i>
                        Security
                    </h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <a href="#password-form" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50/30">
                            <span><i class="fa-solid fa-key mr-2 text-slate-400"></i>Change Password</span>
                            <i class="fa-solid fa-angle-right text-xs text-slate-400"></i>
                        </a>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700">
                            <span><i class="fa-solid fa-user-check mr-2 text-slate-400"></i>Account State</span>
                            <span class="text-[11px] font-bold uppercase tracking-[0.18em] {{ in_array($user->status, ['blocked', 'inactive'], true) ? 'text-red-500' : 'text-emerald-600' }}">
                                {{ in_array($user->status, ['blocked', 'inactive'], true) ? 'Restricted' : 'Active' }}
                            </span>
                        </div>
                        <a href="{{ route('frontend.orders') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50/30">
                            <span><i class="fa-solid fa-box mr-2 text-slate-400"></i>Order Activity</span>
                            <i class="fa-solid fa-angle-right text-xs text-slate-400"></i>
                        </a>
                    </div>
                </div>

                <div class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-chart-line mr-2 text-emerald-500"></i>
                        Account Snapshot
                    </h2>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-sm font-semibold text-slate-600">Delivered Orders</span>
                            <span class="text-sm font-black text-slate-900">{{ $stats['delivered_orders'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-sm font-semibold text-slate-600">Active Orders</span>
                            <span class="text-sm font-black text-slate-900">{{ $stats['active_orders'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-sm font-semibold text-slate-600">Profile Completion</span>
                            <span class="text-sm font-black text-slate-900">{{ $profileCompletion }}%</span>
                        </div>
                    </div>
                </div>

                <div class="rounded-[24px] border border-emerald-200 bg-emerald-50/80 p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-circle-check mr-2 text-emerald-600"></i>
                        Account Status
                    </h2>
                    <p class="mt-2 text-sm text-emerald-900/80">
                        Your account is {{ in_array($user->status, ['blocked', 'inactive'], true) ? 'currently restricted' : 'active and ready for smooth ordering' }}.
                        @if($user->email_verified_at)
                            Email is verified.
                        @else
                            Email verification can be added later for extra trust.
                        @endif
                    </p>
                </div>

                <div id="profile-edit" class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-user-pen mr-2 text-blue-600"></i>
                        Edit Profile
                    </h2>
                    <form method="POST" action="{{ route('frontend.profile.update') }}" class="mt-4 space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Phone</label>
                            <input type="tel" id="profilePhoneInput" name="phone" value="{{ old('phone', $user->phone) }}"
                                   inputmode="numeric" pattern="(?:\d{10}|\d{12})" minlength="10" maxlength="12" autocomplete="off"
                                   title="Phone number must be 10 or 12 digits"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:bg-white">
                            <p class="mt-1 text-[11px] font-semibold text-slate-400">Only 10 or 12 digit numbers are allowed.</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-400">
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-blue-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                            Save Profile
                        </button>
                    </form>
                </div>

                <div id="password-form" class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-lock mr-2 text-slate-700"></i>
                        Update Password
                    </h2>
                    <form method="POST" action="{{ route('frontend.profile.password') }}" class="mt-4 space-y-3">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Current Password</label>
                            <input type="password" name="current_password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">New Password</label>
                            <input type="password" name="password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-blue-400 focus:bg-white">
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-sm font-bold text-white transition hover:bg-slate-800">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="notificationViewModal" class="fixed inset-0 z-[10060] hidden items-center justify-center bg-slate-950/55 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-[24px] bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-blue-600">FarmSea Notification</p>
                <h2 id="notificationViewSubject" class="mt-2 text-xl font-extrabold leading-snug text-slate-900"></h2>
            </div>
            <button type="button" id="notificationViewClose" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200" aria-label="Close notification">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <p id="notificationViewDate" class="mt-3 text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400"></p>
        <p id="notificationViewMessage" class="mt-5 whitespace-pre-wrap text-sm leading-7 text-slate-600"></p>
    </div>
</div>
<script>
const profilePhoneInput = document.getElementById('profilePhoneInput');

function sanitizeProfilePhone() {
    if (!profilePhoneInput) {
        return;
    }

    profilePhoneInput.value = profilePhoneInput.value.replace(/\D/g, '').slice(0, 12);
}

profilePhoneInput?.addEventListener('input', sanitizeProfilePhone);
sanitizeProfilePhone();

const notificationViewModal = document.getElementById('notificationViewModal');
const notificationViewSubject = document.getElementById('notificationViewSubject');
const notificationViewMessage = document.getElementById('notificationViewMessage');
const notificationViewDate = document.getElementById('notificationViewDate');

function closeNotificationView() {
    notificationViewModal?.classList.add('hidden');
    notificationViewModal?.classList.remove('flex');
}

document.querySelectorAll('.notification-view-btn').forEach((button) => {
    button.addEventListener('click', () => {
        notificationViewSubject.textContent = button.dataset.subject || 'Notification';
        notificationViewMessage.textContent = button.dataset.message || '';
        notificationViewDate.textContent = button.dataset.date || '';
        notificationViewModal.classList.remove('hidden');
        notificationViewModal.classList.add('flex');
    });
});

document.getElementById('notificationViewClose')?.addEventListener('click', closeNotificationView);
notificationViewModal?.addEventListener('click', (event) => {
    if (event.target === notificationViewModal) closeNotificationView();
});
</script>
@endsection
