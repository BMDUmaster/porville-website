@extends('frontend.layouts.app')
@section('title', 'Track Order')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-2">Track Your Order</h1>
    <p class="text-gray-500 text-sm mb-8">
        Enter your order number
        @guest('web_frontend')
            and delivery phone number
        @endguest
        to track your delivery status.
    </p>

    <form method="GET" class="bg-white rounded-2xl border p-6 mb-6">
        <div class="grid gap-4 md:grid-cols-2">
            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Order Number</label>
                <input type="text" name="order_number" value="{{ request('order_number') }}"
                       placeholder="e.g. ORD-ABCDEF123456"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm outline-none focus:border-blue-500">
            </div>
            @guest('web_frontend')
            <div class="md:col-span-1">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Delivery Phone</label>
                <input type="text" name="phone" value="{{ request('phone') }}"
                       placeholder="Enter delivery phone number"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm outline-none focus:border-blue-500">
            </div>
            @endguest
        </div>
        <div class="mt-4 flex justify-end">
            <button type="submit" class="bg-blue-700 hover:bg-blue-800 text-white font-bold px-6 py-3 rounded-xl text-sm transition">
                Track
            </button>
        </div>
        @guest('web_frontend')
            <p class="mt-3 text-xs text-gray-400">For guest tracking, phone number must match the order delivery phone.</p>
        @endguest
    </form>

    @if(request('order_number'))
        @if($order)
        <div class="bg-white rounded-2xl border p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">{{ $order->order_number }}</h2>
                <span class="text-xs font-bold px-3 py-1 rounded-full {{ $order->status_badge_class }}">
                    {{ $order->status_label }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mb-4">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>

            {{-- Status Timeline --}}
            @php
                $steps = ['pending','confirmed','processing','out_for_delivery','delivered'];
                $currentIdx = array_search($order->status, $steps);
            @endphp
            <div class="flex items-center gap-0 mb-6">
                @foreach($steps as $idx => $step)
                <div class="flex items-center {{ $idx < count($steps)-1 ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $currentIdx !== false && $idx <= $currentIdx ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                            @if($currentIdx !== false && $idx < $currentIdx)
                                <i class="fa-solid fa-check text-xs"></i>
                            @else
                                {{ $idx + 1 }}
                            @endif
                        </div>
                        <p class="mt-1 whitespace-nowrap text-[9px] text-gray-500">{{ ucwords(str_replace('_', ' ', $step)) }}</p>
                    </div>
                    @if($idx < count($steps)-1)
                    <div class="flex-1 h-0.5 mx-1 {{ $currentIdx !== false && $idx < $currentIdx ? 'bg-green-600' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
                @endforeach
            </div>

            <div class="border-t pt-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">Order Items</p>
                @foreach($order->items as $item)
                <div class="flex items-center gap-3 py-2 border-b last:border-b-0">
                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0">
                        @if($item->product && $item->product->images)
                            <img src="{{ asset('storage/'.$item->product->images[0]) }}" class="w-full h-full object-cover rounded-lg">
                        @else
                            <span>🥩</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ $item->product->name ?? 'Product' }}</p>
                        <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                    </div>
                    <p class="text-sm font-bold text-gray-800">₹{{ number_format($item->subtotal, 2) }}</p>
                </div>
                @endforeach
                <div class="flex justify-between pt-3 font-bold text-gray-800">
                    <span>Total</span>
                    <span>₹{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>
        @else
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
            <i class="fa-solid fa-circle-xmark text-red-400 text-3xl mb-3 block"></i>
            <p class="font-semibold text-red-700">Order not found</p>
            <p class="text-sm text-red-500 mt-1">Please check your order number and try again.</p>
        </div>
        @endif
    @endif
</div>
@endsection
