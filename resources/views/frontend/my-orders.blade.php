@extends('frontend.layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">Order History</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach([['Total Orders',$stats['total'],'fa-box','blue'],['Delivered',$stats['delivered'],'fa-check-circle','green'],['In Progress',$stats['active'],'fa-hourglass-half','orange'],['Total Spent','₹'.number_format($stats['spent'],0),'fa-wallet','purple']] as [$label,$val,$icon,$color])
        <div class="bg-white rounded-2xl border p-5">
            <div class="flex items-start justify-between mb-3">
                <div>
                    <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">{{ $label }}</p>
                    <p class="nunito font-extrabold text-2xl text-gray-800">{{ $val }}</p>
                </div>
                <div class="w-10 h-10 bg-{{ $color }}-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid {{ $icon }} text-{{ $color }}-600"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-2xl border p-5 mb-6 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order number..."
               class="flex-1 min-w-[200px] px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none focus:border-blue-500">
        <select name="status" onchange="this.form.submit()" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm outline-none">
            <option value="">All Orders</option>
            @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold">Search</button>
    </form>

    {{-- Orders Table --}}
    <div class="bg-white rounded-2xl border overflow-hidden">
        <table class="w-full min-w-[600px]">
            <thead class="bg-gray-50 border-b">
                <tr class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 text-left">Order ID</th>
                    <th class="px-6 py-4 text-left">Date</th>
                    <th class="px-6 py-4 text-left">Items</th>
                    <th class="px-6 py-4 text-left">Status</th>
                    <th class="px-6 py-4 text-right">Amount</th>
                    <th class="px-6 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <a href="{{ route('frontend.order.show', $order->id) }}" class="font-bold text-blue-700 hover:underline text-sm">
                            {{ $order->order_number ?? '#'.$order->id }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->items->count() }} item(s)</td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ $order->status_badge_class }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-blue-700">₹{{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('frontend.order.show', $order->id) }}"
                           class="px-3 py-1.5 bg-blue-700 text-white text-xs font-bold rounded-lg hover:bg-blue-800 transition">
                            <i class="fa-solid fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-400">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
