@extends('frontend.layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-8">
    <h1 class="mb-6 text-2xl font-extrabold text-gray-800 nunito">Order History</h1>

    <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
        @foreach([['Total Orders',$stats['total'],'fa-box','blue'],['Delivered',$stats['delivered'],'fa-check-circle','green'],['In Progress',$stats['active'],'fa-hourglass-half','orange'],['Total Spent','Rs'.number_format($stats['spent'],0),'fa-wallet','purple']] as [$label,$val,$icon,$color])
        <div class="rounded-2xl border bg-white p-5">
            <div class="mb-3 flex items-start justify-between">
                <div>
                    <p class="mb-1 text-xs font-semibold uppercase tracking-wider text-gray-500">{{ $label }}</p>
                    <p class="text-2xl font-extrabold text-gray-800 nunito">{{ $val }}</p>
                </div>
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-{{ $color }}-100">
                    <i class="fa-solid {{ $icon }} text-{{ $color }}-600"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <form method="GET" class="mb-6 flex flex-col gap-3 rounded-2xl border bg-white p-5 sm:flex-row sm:flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order number..."
               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none focus:border-blue-500 sm:min-w-[200px] sm:flex-1">
        <select name="status" onchange="this.form.submit()" class="rounded-xl border border-gray-200 px-4 py-2.5 text-sm outline-none">
            <option value="">All Orders</option>
            @foreach(['pending','confirmed','processing','out_for_delivery','delivered','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $s)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl bg-blue-700 px-5 py-2.5 text-sm font-bold text-white">Search</button>
    </form>

    <div class="space-y-4 md:hidden">
        @forelse($orders as $order)
        <article class="rounded-2xl border bg-white p-4">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p class="break-all text-sm font-bold text-blue-700">{{ $order->order_number ?? '#'.$order->id }}</p>
                    <p class="mt-1 text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                </div>
                <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $order->status_badge_class }}">
                    {{ $order->status_label }}
                </span>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Items</p>
                    <p class="mt-1 text-sm text-gray-700">{{ $order->items->count() }} item(s)</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Amount</p>
                    <p class="mt-1 text-sm font-bold text-blue-700">Rs{{ number_format($order->total, 2) }}</p>
                </div>
            </div>

            <a href="{{ route('frontend.track', ['order_number' => $order->order_number]) }}"
               class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-blue-700 px-3 py-2.5 text-xs font-bold text-white transition hover:bg-blue-800">
                <i class="fa-solid fa-eye"></i> View
            </a>
        </article>
        @empty
        <div class="rounded-2xl border bg-white px-6 py-12 text-center text-gray-400">No orders found.</div>
        @endforelse
    </div>

    <div class="hidden overflow-hidden rounded-2xl border bg-white md:block">
        <table class="w-full min-w-[600px]">
            <thead class="border-b bg-gray-50">
                <tr class="text-xs font-bold uppercase tracking-wider text-gray-500">
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
                <tr class="transition hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <a href="{{ route('frontend.order.show', $order->id) }}" class="text-sm font-bold text-blue-700 hover:underline">
                            {{ $order->order_number ?? '#'.$order->id }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->items->count() }} item(s)</td>
                    <td class="px-6 py-4">
                        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $order->status_badge_class }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-blue-700">Rs{{ number_format($order->total, 2) }}</td>
                    <td class="px-6 py-4 text-center">
                        <a href="{{ route('frontend.track', ['order_number' => $order->order_number]) }}"
                           class="rounded-lg bg-blue-700 px-3 py-1.5 text-xs font-bold text-white transition hover:bg-blue-800">
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
