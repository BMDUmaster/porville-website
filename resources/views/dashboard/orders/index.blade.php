@extends('layouts.dashboard')
@section('title', 'Orders')
@section('page_title', 'FarmSea Orders')

@section('content')
@php
    $statusOptions = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
@endphp
<div class="p-4 md:p-8 space-y-6">

    <form method="GET" class="flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold text-gray-800">Live Orders</h1>
        <div class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer / ID..."
                   class="border rounded px-3 py-1.5 text-sm outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="border rounded px-3 py-1.5 text-sm outline-none">
                <option value="">All Status</option>
                @foreach($statusOptions as $status)
                    <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="rounded bg-blue-600 px-4 py-1.5 text-sm text-white">Filter</button>
            <a href="{{ route('dashboard.orders') }}" class="rounded bg-gray-200 px-4 py-1.5 text-sm text-gray-700">Reset</a>
        </div>
    </form>

    <div class="overflow-x-auto rounded-lg border bg-white shadow-sm">
        <table class="w-full min-w-[1180px] text-left">
            <thead class="border-b bg-gray-50">
                <tr>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Order ID</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Date & Time</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Customer</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Items</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Total</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Payment</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Delivery Boy</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Status</th>
                    <th class="px-4 py-4 text-center text-xs font-bold uppercase text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($orders as $order)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-4 text-sm font-bold text-blue-600">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            <p>{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('h:i A') }}</p>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-800">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-600">{{ $order->items->count() }} item(s)</td>
                        <td class="px-4 py-4 text-sm font-bold text-gray-900">Rs{{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-4 text-xs font-semibold uppercase text-gray-600">{{ $order->payment_method ?? 'COD' }}</td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            @if($order->deliveryBoy)
                                <div class="font-bold text-gray-800">{{ $order->deliveryBoy->partner_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->deliveryBoy->phone_number }}</div>
                                <div class="text-xs text-gray-400">{{ $order->deliveryBoy->area }}</div>
                            @else
                                <span class="text-xs text-gray-400">Not assigned</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-2 py-1 text-[10px] font-bold uppercase {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-start justify-center gap-2">
                                <a href="{{ route('dashboard.orders.show', $order) }}"
                                   class="mt-[28px] rounded-lg bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-600 hover:bg-blue-100">
                                    <i class="fa-solid fa-eye"></i> View
                                </a>

                                <form method="POST" action="{{ route('dashboard.orders.status', $order) }}" class="inline-flex flex-col gap-2 rounded-lg border border-gray-200 bg-gray-50 p-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="toggleDeliveryBoySelect(this, '{{ $order->id }}')" class="border rounded px-2 py-1 text-xs outline-none">
                                        @foreach($statusOptions as $status)
                                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                                {{ ucwords(str_replace('_', ' ', $status)) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="delivery_boy_id" id="delivery-boy-select-{{ $order->id }}"
                                            class="border rounded px-2 py-1 text-xs outline-none {{ $order->status === 'out_for_delivery' ? '' : 'hidden' }}">
                                        <option value="">Choose delivery boy</option>
                                        @foreach($deliveryBoysByOrder[$order->id] ?? [] as $deliveryBoy)
                                            <option value="{{ $deliveryBoy->id }}" {{ $order->delivery_boy_id === $deliveryBoy->id ? 'selected' : '' }}>
                                                {{ $deliveryBoy->partner_name }} | {{ $deliveryBoy->phone_number }} | {{ $deliveryBoy->area }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="rounded bg-blue-600 px-3 py-1 text-[11px] font-bold text-white hover:bg-blue-700">
                                        Save
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-400">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $orders->withQueryString()->links() }}</div>
</div>
@endsection

@section('scripts')
<script>
function toggleDeliveryBoySelect(select, orderId) {
    const deliverySelect = document.getElementById(`delivery-boy-select-${orderId}`);

    if (!deliverySelect) {
        return;
    }

    const shouldShow = select.value === 'out_for_delivery';
    deliverySelect.classList.toggle('hidden', !shouldShow);

    if (!shouldShow) {
        deliverySelect.value = '';
    }
}
</script>
@endsection
