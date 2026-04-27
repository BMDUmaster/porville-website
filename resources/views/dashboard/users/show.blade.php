@extends('layouts.dashboard')
@section('title', 'User Details')
@section('page_title', 'User Details')

@section('content')
<div class="p-4 md:p-8 space-y-6">

    <a href="{{ route('dashboard.users') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:underline">
        <i class="fa-solid fa-arrow-left text-xs"></i> Back to Users
    </a>

    <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-[linear-gradient(135deg,#eff6ff_0%,#ffffff_50%,#ecfdf5_100%)] shadow-sm">
        <div class="grid gap-6 p-6 lg:grid-cols-[minmax(0,1.15fr)_360px] lg:p-8">
            <div>
                <div class="flex flex-wrap items-start gap-4">
                    <div class="flex h-20 w-20 items-center justify-center rounded-3xl bg-blue-600 text-3xl font-black text-white shadow-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-white px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-slate-500 shadow-sm">
                                Customer ID #{{ $user->id }}
                            </span>
                            <span class="rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($user->status ?? 'active') }}
                            </span>
                            <span class="rounded-full bg-blue-100 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-blue-700">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                        <h1 class="mt-4 text-3xl font-black tracking-tight text-slate-900">{{ $user->name }}</h1>
                        <p class="mt-2 text-sm font-semibold text-slate-500">{{ $user->email }}</p>
                        <div class="mt-4 flex flex-wrap gap-3 text-sm text-slate-600">
                            <span class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 shadow-sm">
                                <i class="fa-solid fa-phone text-slate-400"></i>
                                {{ $user->phone ?: 'Phone not added' }}
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 shadow-sm">
                                <i class="fa-solid fa-calendar-days text-slate-400"></i>
                                Joined {{ $user->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-4 xl:grid-cols-4">
                    <div class="rounded-2xl border bg-white p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Total Orders</p>
                        <p class="mt-2 text-3xl font-black text-slate-900">{{ $stats['total_orders'] }}</p>
                    </div>
                    <div class="rounded-2xl border bg-white p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Delivered</p>
                        <p class="mt-2 text-3xl font-black text-emerald-600">{{ $stats['delivered_orders'] }}</p>
                    </div>
                    <div class="rounded-2xl border bg-white p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Active Orders</p>
                        <p class="mt-2 text-3xl font-black text-amber-600">{{ $stats['active_orders'] }}</p>
                    </div>
                    <div class="rounded-2xl border bg-white p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Total Spent</p>
                        <p class="mt-2 text-3xl font-black text-indigo-600">Rs{{ number_format($stats['total_spent'], 2) }}</p>
                    </div>
                </div>
            </div>

            <aside class="space-y-4">
                <div class="rounded-[24px] border bg-white p-5 shadow-sm">
                    <h2 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Account Actions</h2>
                    <div class="mt-4 flex flex-col gap-3">
                        <form method="POST" action="{{ route('dashboard.users.toggle', $user) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="w-full rounded-xl px-5 py-3 text-sm font-black {{ $user->status === 'active' ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                                {{ $user->status === 'active' ? 'Block User' : 'Unblock User' }}
                            </button>
                        </form>
                        <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                            Last updated on {{ $user->updated_at->format('d M Y, h:i A') }}
                        </div>
                    </div>
                </div>

                <div class="rounded-[24px] border bg-white p-5 shadow-sm">
                    <h2 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Latest Order Snapshot</h2>
                    @if($latestOrder)
                        <div class="mt-4 space-y-3 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Order</span>
                                <a href="{{ route('dashboard.orders.show', $latestOrder) }}" class="font-black text-blue-600 hover:underline">
                                    {{ $latestOrder->order_number ?? '#ORD-' . str_pad($latestOrder->id, 4, '0', STR_PAD_LEFT) }}
                                </a>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Amount</span>
                                <span class="font-black text-slate-900">Rs{{ number_format($latestOrder->total, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Payment</span>
                                <span class="font-bold uppercase text-slate-700">{{ $latestOrder->payment_method ?? 'COD' }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Status</span>
                                <span class="rounded-full px-3 py-1 text-xs font-black {{ $latestOrder->status_badge_class }}">
                                    {{ $latestOrder->status_label }}
                                </span>
                            </div>
                            <div class="border-t pt-3">
                                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Shipping Address</p>
                                <p class="mt-2 leading-6 text-slate-600">
                                    @if(is_array($latestOrder->shipping_address))
                                        {{ implode(', ', array_filter($latestOrder->shipping_address)) }}
                                    @else
                                        {{ $latestOrder->shipping_address ?: 'No address available.' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-400">No orders placed yet.</p>
                    @endif
                </div>
            </aside>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <div class="rounded-[24px] border bg-white shadow-sm">
            <div class="border-b px-6 py-5">
                <h2 class="text-lg font-black text-slate-900">Full Order History</h2>
                <p class="mt-1 text-sm text-slate-500">User ke saare orders, payment aur delivery assignment details.</p>
            </div>
            <div class="space-y-4 p-4 md:hidden">
                @forelse($user->orders as $order)
                    <article class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="font-black text-blue-600 break-all">{{ $order->order_number ?? '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="mt-1 text-xs text-slate-400">ID {{ $order->id }}</div>
                            </div>
                            <span class="shrink-0 rounded-full px-3 py-1 text-xs font-black {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                            <div>
                                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">Placed On</p>
                                <p class="mt-1 text-sm font-bold text-slate-800">{{ $order->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $order->created_at->format('h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">Amount</p>
                                <p class="mt-1 text-sm font-black text-slate-900">Rs{{ number_format($order->total, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">Items</p>
                                <p class="mt-1 text-sm font-bold text-slate-800">{{ $order->items->count() }} item(s)</p>
                                <p class="mt-1 text-xs text-slate-400">
                                    {{ $order->items->pluck('product.name')->filter()->take(2)->implode(', ') ?: 'No item names' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">Payment</p>
                                <p class="mt-1 text-sm font-bold uppercase text-slate-800">{{ $order->payment_method ?? 'COD' }}</p>
                                <p class="text-xs uppercase text-slate-400">{{ $order->payment_status ?? 'pending' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400">Delivery Boy</p>
                                @if($order->deliveryBoy)
                                    <p class="mt-1 text-sm font-bold text-slate-800">{{ $order->deliveryBoy->partner_name }}</p>
                                    <p class="text-xs text-slate-400">{{ $order->deliveryBoy->phone_number }}</p>
                                @else
                                    <p class="mt-1 text-xs text-slate-400">Not assigned</p>
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('dashboard.orders.show', $order) }}"
                           class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-50 px-3 py-3 text-xs font-black text-blue-600 hover:bg-blue-100">
                            <i class="fa-solid fa-eye"></i> View Order
                        </a>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-10 text-center text-slate-400">
                        No orders yet.
                    </div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="w-full min-w-[1080px] text-sm">
                    <thead class="border-b bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-[0.16em]">Order</th>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-[0.16em]">Placed On</th>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-[0.16em]">Items</th>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-[0.16em]">Delivery Boy</th>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-[0.16em]">Payment</th>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-[0.16em]">Amount</th>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-[0.16em]">Status</th>
                            <th class="px-4 py-4 text-left text-xs font-black uppercase tracking-[0.16em]">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($user->orders as $order)
                            <tr class="hover:bg-slate-50/80">
                                <td class="px-4 py-4">
                                    <div class="font-black text-blue-600">{{ $order->order_number ?? '#ORD-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    <div class="mt-1 text-xs text-slate-400">ID {{ $order->id }}</div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="mt-1 text-xs text-slate-400">{{ $order->created_at->format('h:i A') }}</div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    <div class="font-bold text-slate-800">{{ $order->items->count() }} item(s)</div>
                                    <div class="mt-1 text-xs text-slate-400">
                                        {{ $order->items->pluck('product.name')->filter()->take(2)->implode(', ') ?: 'No item names' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    @if($order->deliveryBoy)
                                        <div class="font-bold text-slate-800">{{ $order->deliveryBoy->partner_name }}</div>
                                        <div class="mt-1 text-xs text-slate-400">{{ $order->deliveryBoy->phone_number }}</div>
                                    @else
                                        <span class="text-xs text-slate-400">Not assigned</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-600">
                                    <div class="font-bold uppercase text-slate-800">{{ $order->payment_method ?? 'COD' }}</div>
                                    <div class="mt-1 text-xs uppercase text-slate-400">{{ $order->payment_status ?? 'pending' }}</div>
                                </td>
                                <td class="px-4 py-4 font-black text-slate-900">Rs{{ number_format($order->total, 2) }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-black {{ $order->status_badge_class }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <a href="{{ route('dashboard.orders.show', $order) }}"
                                       class="inline-flex items-center gap-2 rounded-xl bg-blue-50 px-3 py-2 text-xs font-black text-blue-600 hover:bg-blue-100">
                                        <i class="fa-solid fa-eye"></i> View Order
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-slate-400">No orders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-[24px] border bg-white p-5 shadow-sm">
                <h2 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Account Information</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Full Name</p>
                        <p class="mt-1 text-sm font-bold text-slate-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Email Address</p>
                        <p class="mt-1 break-all text-sm font-bold text-slate-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Phone</p>
                        <p class="mt-1 text-sm font-bold text-slate-900">{{ $user->phone ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Email Verified</p>
                        <p class="mt-1 text-sm font-bold text-slate-900">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('d M Y, h:i A') : 'Not verified' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Joined</p>
                        <p class="mt-1 text-sm font-bold text-slate-900">{{ $user->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Last Updated</p>
                        <p class="mt-1 text-sm font-bold text-slate-900">{{ $user->updated_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[24px] border bg-white p-5 shadow-sm">
                <h2 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Order Summary</h2>
                <div class="mt-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <span class="text-slate-500">Total Orders</span>
                        <span class="font-black text-slate-900">{{ $stats['total_orders'] }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <span class="text-slate-500">Delivered Orders</span>
                        <span class="font-black text-emerald-600">{{ $stats['delivered_orders'] }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <span class="text-slate-500">Active Orders</span>
                        <span class="font-black text-amber-600">{{ $stats['active_orders'] }}</span>
                    </div>
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3">
                        <span class="text-slate-500">Lifetime Spend</span>
                        <span class="font-black text-indigo-600">Rs{{ number_format($stats['total_spent'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
