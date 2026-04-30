@extends('frontend.layouts.app')
@section('title', 'Track Order')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="nunito mb-2 text-2xl font-extrabold text-gray-800">Track Your Order</h1>
    <p class="mb-8 text-sm text-gray-500">
        Enter your order number
        @guest('web_frontend')
            and delivery phone number
        @endguest
        to track your delivery status.
    </p>

    <form method="GET" class="mb-6 rounded-2xl border bg-white p-6">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-1">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Order Number</label>
                <input type="text" name="order_number" value="{{ request('order_number') }}"
                       placeholder="e.g. ORD-ABCDEF123456"
                       class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-blue-500">
            </div>
            @guest('web_frontend')
            <div class="md:col-span-1">
                <label class="mb-2 block text-sm font-semibold text-gray-700">Delivery Phone</label>
                <input type="text" name="phone" value="{{ request('phone') }}"
                       placeholder="Enter delivery phone number"
                       class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm outline-none focus:border-blue-500">
            </div>
            @endguest
        </div>
        <div class="mt-4 flex justify-end">
            <button type="submit" class="rounded-xl bg-blue-700 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                Track
            </button>
        </div>
        @guest('web_frontend')
            <p class="mt-3 text-xs text-gray-400">For guest tracking, phone number must match the order delivery phone.</p>
        @endguest
    </form>

    @if(request('order_number'))
        @if($order)
        <div class="rounded-2xl border bg-white p-6">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-bold text-gray-800">{{ $order->order_number }}</h2>
                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->status_badge_class }}">
                    {{ $order->status_label }}
                </span>
            </div>
            <p class="mb-4 text-sm text-gray-500">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
            @if($order->delivery_slot_label)
                <p class="mb-4 inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                    <i class="fa-regular fa-clock"></i>
                    Preferred delivery: {{ $order->delivery_slot_label }}
                </p>
            @endif

            @php
                $steps = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'delivered'];
                $currentIdx = array_search($order->status, $steps);
            @endphp
            <div class="mb-6 flex items-center gap-0">
                @foreach($steps as $idx => $step)
                <div class="flex items-center {{ $idx < count($steps) - 1 ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold
                            {{ $currentIdx !== false && $idx <= $currentIdx ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if($currentIdx !== false && $idx < $currentIdx)
                                <i class="fa-solid fa-check text-xs"></i>
                            @else
                                {{ $idx + 1 }}
                            @endif
                        </div>
                        <p class="mt-1 whitespace-nowrap text-[9px] text-gray-500">{{ ucwords(str_replace('_', ' ', $step)) }}</p>
                    </div>
                    @if($idx < count($steps) - 1)
                    <div class="mx-1 h-0.5 flex-1 {{ $currentIdx !== false && $idx < $currentIdx ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="border-t pt-4">
                <p class="mb-2 text-sm font-semibold text-gray-700">Order Items</p>
                @foreach($order->items as $item)
                <div class="flex items-center gap-3 border-b py-2 last:border-b-0">
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100">
                        @if($item->product && $item->product->images)
                            <img src="{{ asset('storage/' . $item->product->images[0]) }}" class="h-full w-full rounded-lg object-cover" alt="{{ $item->product->name ?? 'Product' }}">
                        @else
                            <i class="fa-solid fa-basket-shopping text-sm text-gray-400"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $item->product->name ?? 'Product' }}</p>
                        <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                    </div>
                    <p class="text-sm font-bold text-gray-800">&#8377;{{ number_format($item->subtotal, 2) }}</p>
                </div>
                @endforeach
                <div class="flex justify-between pt-3 font-bold text-gray-800">
                    <span>Total</span>
                    <span>&#8377;{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
        @else
        <div class="rounded-2xl border border-red-200 bg-red-50 p-6 text-center">
            <i class="fa-solid fa-circle-xmark mb-3 block text-3xl text-red-400"></i>
            <p class="font-semibold text-red-700">Order not found</p>
            <p class="mt-1 text-sm text-red-500">Please check your order number and try again.</p>
        </div>
        @endif
    @endif
</div>
@endsection
