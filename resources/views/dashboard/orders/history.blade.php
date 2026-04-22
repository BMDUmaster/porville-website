@extends('layouts.dashboard')
@section('title', 'Order History')
@section('page_title', 'Order History')

@section('content')
<div class="p-4 md:p-8 space-y-6">

    {{-- Stats --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-5">
        @foreach([["Today's Orders",$stats['today'],'purple','fa-calendar-day'],['Pending',$stats['pending'],'emerald','fa-hourglass-split'],['Delivered',$stats['delivered'],'blue','fa-bag-check'],['Cancelled',$stats['cancelled'],'red','fa-circle-xmark']] as [$label,$val,$color,$icon])
        <div class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-{{ $color }}-500 flex items-center justify-center text-white shadow-lg">
                <i class="fa-solid {{ $icon }} text-xl"></i>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase">{{ $label }}</p>
                <h2 class="text-2xl font-bold text-gray-800">{{ $val }}</h2>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="grid grid-cols-2 md:grid-cols-6 gap-3 bg-white p-4 rounded-2xl shadow-sm border">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search"
               class="col-span-2 md:col-span-1 px-4 py-2.5 rounded-full bg-gray-50 border text-sm outline-none">
        <input type="text" name="order_id" value="{{ request('order_id') }}" placeholder="Order ID"
               class="px-4 py-2.5 rounded-full bg-gray-50 border text-sm outline-none">
        <select name="status" class="px-4 py-2.5 rounded-full bg-gray-50 border text-sm outline-none">
            <option value="">All Status</option>
            @foreach(['completed','pending','processing','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <input type="date" name="date_from" value="{{ request('date_from') }}"
               class="px-4 py-2.5 rounded-full bg-gray-50 border text-sm outline-none">
        <input type="date" name="date_to" value="{{ request('date_to') }}"
               class="px-4 py-2.5 rounded-full bg-gray-50 border text-sm outline-none">
        <button type="submit" class="px-4 py-2.5 rounded-full bg-indigo-600 text-white text-sm font-semibold">Filter</button>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border overflow-x-auto">
        <table class="w-full text-sm text-left min-w-[900px]">
            <thead class="bg-indigo-600 text-white uppercase text-xs tracking-wider">
                <tr>
                    <th class="px-4 py-4">Sr.No.</th>
                    <th class="px-4 py-4">Date & Time</th>
                    <th class="px-4 py-4">Customer</th>
                    <th class="px-4 py-4">Order ID</th>
                    <th class="px-4 py-4">Amount</th>
                    <th class="px-4 py-4">Status</th>
                    <th class="px-4 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $i => $order)
                <tr class="hover:bg-gray-50 transition">
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
                        <div class="font-bold text-indigo-600">₹{{ number_format($order->total, 2) }}</div>
                        <span class="text-xs px-2 py-0.5 rounded bg-blue-50 text-blue-600 font-bold uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1 text-xs font-bold rounded-full {{ $order->status_badge_class }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <a href="{{ route('dashboard.orders.show', $order) }}"
                           class="px-4 py-2 hover:bg-indigo-50 text-indigo-600 rounded-lg flex items-center gap-2 text-sm font-medium">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No order history found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>{{ $orders->withQueryString()->links() }}</div>
</div>
@endsection
