@extends('layouts.dashboard')
@section('title', 'Order History')
@section('page_title', 'Order History')

@section('content')
@php
    $statusOptions = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
    $dateFilterOptions = [
        '' => 'All Dates',
        'today' => 'Today',
        'last_7_days' => 'Last 7 Days',
        'last_30_days' => 'Last 30 Days',
        'custom' => 'Custom Range',
    ];
@endphp
<div class="p-4 md:p-8 space-y-6">

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([["Today's Orders",$stats['today'],'purple','fa-calendar-day'],['Pending',$stats['pending'],'emerald','fa-hourglass-split'],['Delivered',$stats['delivered'],'blue','fa-bag-check'],['Cancelled',$stats['cancelled'],'red','fa-circle-xmark']] as [$label,$val,$color,$icon])
            <div class="flex items-center gap-4 rounded-2xl border bg-white p-5 shadow-sm">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-{{ $color }}-500 text-white shadow-lg">
                    <i class="fa-solid {{ $icon }} text-xl"></i>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase text-gray-500">{{ $label }}</p>
                    <h2 class="text-2xl font-bold text-gray-800">{{ $val }}</h2>
                </div>
            </div>
        @endforeach
    </div>

    <form method="GET" class="grid grid-cols-1 gap-3 rounded-2xl border bg-white p-4 shadow-sm sm:grid-cols-2 xl:grid-cols-8">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search"
               class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none sm:col-span-2 xl:col-span-1">
        <input type="text" name="order_id" value="{{ request('order_id') }}" placeholder="Order ID"
               class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
        <select name="status" class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
            <option value="">All Status</option>
            @foreach($statusOptions as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                    {{ ucwords(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
        <select name="date_filter" id="historyDateFilter" onchange="toggleHistoryCustomDates()" class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
            @foreach($dateFilterOptions as $value => $label)
                <option value="{{ $value }}" {{ request('date_filter') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        <div id="historyCustomDateFields" class="{{ request('date_filter') === 'custom' ? 'contents' : 'hidden' }}">
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                   class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                   class="rounded-full border bg-gray-50 px-4 py-2.5 text-sm outline-none">
        </div>
        <button type="submit" class="rounded-full bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white">Filter</button>
        <a href="{{ route('dashboard.orders.history') }}" class="rounded-full bg-gray-100 px-4 py-2.5 text-center text-sm font-semibold text-gray-600">Reset</a>
    </form>

    <div class="space-y-4 md:hidden">
        @forelse($orders as $i => $order)
            <article class="rounded-2xl border bg-white p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-gray-400">#{{ $orders->firstItem() + $i }}</p>
                        <p class="mt-1 break-all text-sm font-bold text-indigo-600">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Date</p>
                        <p class="mt-1 text-sm font-semibold text-gray-800">{{ $order->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Amount</p>
                        <p class="mt-1 text-sm font-bold text-indigo-600">Rs{{ number_format($order->total, 2) }}</p>
                        <p class="text-xs font-bold uppercase text-blue-600">{{ $order->payment_method ?? 'COD' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Customer</p>
                        <p class="mt-1 text-sm font-bold text-gray-800">{{ $order->user->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-500">ID: {{ $order->user_id }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Delivery Boy</p>
                        @if($order->deliveryBoy)
                            <p class="mt-1 text-sm font-bold text-gray-800">{{ $order->deliveryBoy->partner_name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->deliveryBoy->area }}</p>
                        @else
                            <p class="mt-1 text-xs text-gray-400">Not assigned</p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('dashboard.orders.show', $order) }}"
                   class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-indigo-50 px-4 py-2.5 text-sm font-medium text-indigo-600 hover:bg-indigo-100">
                    <i class="fas fa-eye"></i> View
                </a>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed bg-white px-4 py-10 text-center text-gray-400">No order history found.</div>
        @endforelse
    </div>

    <div class="hidden overflow-x-auto rounded-2xl border bg-white shadow-sm md:block">
        <table class="w-full min-w-[1040px] text-left text-sm">
            <thead class="bg-indigo-600 text-xs uppercase tracking-wider text-white">
                <tr>
                    <th class="px-4 py-4">Sr.No.</th>
                    <th class="px-4 py-4">Date & Time</th>
                    <th class="px-4 py-4">Customer</th>
                    <th class="px-4 py-4">Order ID</th>
                    <th class="px-4 py-4">Amount</th>
                    <th class="px-4 py-4">Delivery Boy</th>
                    <th class="px-4 py-4">Status</th>
                    <th class="px-4 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $i => $order)
                    <tr class="transition hover:bg-gray-50">
                        <td class="px-4 py-4 font-medium">{{ $orders->firstItem() + $i }}</td>
                        <td class="px-4 py-4">
                            <span class="font-semibold">{{ $order->created_at->format('d M Y') }}</span><br>
                            <span class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="font-bold text-gray-800">{{ $order->user->name ?? 'Guest' }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $order->user_id }}</div>
                        </td>
                        <td class="px-4 py-4 font-bold text-indigo-600">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-4">
                            <div class="font-bold text-indigo-600">Rs{{ number_format($order->total, 2) }}</div>
                            <span class="rounded bg-blue-50 px-2 py-0.5 text-xs font-bold uppercase text-blue-600">{{ $order->payment_method ?? 'COD' }}</span>
                        </td>
                        <td class="px-4 py-4 text-sm text-gray-600">
                            @if($order->deliveryBoy)
                                <div class="font-bold text-gray-800">{{ $order->deliveryBoy->partner_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->deliveryBoy->area }}</div>
                            @else
                                <span class="text-xs text-gray-400">Not assigned</span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('dashboard.orders.show', $order) }}"
                               class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-indigo-600 hover:bg-indigo-50">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-400">No order history found.</td>
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
function toggleHistoryCustomDates() {
    const filter = document.getElementById('historyDateFilter');
    const customFields = document.getElementById('historyCustomDateFields');

    if (!filter || !customFields) {
        return;
    }

    customFields.classList.toggle('hidden', filter.value !== 'custom');
    customFields.classList.toggle('contents', filter.value === 'custom');
}

toggleHistoryCustomDates();
</script>
@endsection
