@extends('layouts.dashboard')
@section('title', 'Orders')
@section('page_title', 'FarmSea Orders')

@section('content')
<div class="p-4 md:p-8 space-y-6">

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3 items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Live Orders</h1>
        <div class="flex items-center gap-3 flex-wrap">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer / ID..."
                   class="border rounded px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            <select name="status" class="border rounded px-3 py-1.5 text-sm outline-none">
                <option value="">All Status</option>
                @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm">Filter</button>
            <a href="{{ route('dashboard.orders') }}" class="bg-gray-200 text-gray-700 px-4 py-1.5 rounded text-sm">Reset</a>
        </div>
    </form>

    <div class="bg-white rounded-lg shadow-sm border overflow-x-auto">
        <table class="w-full text-left min-w-[900px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Order ID</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Date & Time</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Customer</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Items</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Total</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Payment</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Status</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-4 text-sm font-bold text-blue-600">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600">
                        <p>{{ $order->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('h:i A') }}</p>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-800">{{ $order->user->name ?? 'Guest' }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600">{{ $order->items->count() }} item(s)</td>
                    <td class="px-4 py-4 text-sm font-bold text-gray-900">₹{{ number_format($order->total, 2) }}</td>
                    <td class="px-4 py-4 text-xs font-semibold text-gray-600 uppercase">{{ $order->payment_method ?? 'COD' }}</td>
                    <td class="px-4 py-4">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $order->status_badge_class }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="{{ route('dashboard.orders.show', $order) }}"
                               class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100">
                                <i class="fa-solid fa-eye"></i> View
                            </a>
                            <form method="POST" action="{{ route('dashboard.orders.status', $order) }}" class="inline">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                        class="border rounded px-2 py-1 text-xs outline-none">
                                    @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                                        <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $orders->withQueryString()->links() }}</div>
</div>
@endsection
