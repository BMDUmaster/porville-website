@extends('frontend.layouts.app')
@section('title', 'Order Details')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('frontend.orders') }}" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Orders
        </a>
    </div>

    <div class="bg-white rounded-2xl border overflow-hidden">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <div>
                <h1 class="font-bold text-gray-800">{{ $order->order_number ?? '#'.$order->id }}</h1>
                <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <span class="text-xs font-bold px-3 py-1.5 rounded-full {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>
        </div>

        {{-- Items --}}
        <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-700 mb-3 text-sm flex items-center gap-2">
                <i class="fa-solid fa-cart-shopping text-teal-600"></i> Products
            </h2>
            <table class="w-full text-sm">
                <thead class="border-b">
                    <tr class="text-xs font-bold text-gray-500 uppercase">
                        <th class="pb-2 text-left">Product</th>
                        <th class="pb-2 text-left">QTY</th>
                        <th class="pb-2 text-left">Unit Price</th>
                        <th class="pb-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-3 font-medium text-gray-800">{{ $item->product->name ?? 'Deleted Product' }}</td>
                        <td class="py-3 text-gray-600">{{ $item->quantity }}</td>
                        <td class="py-3 text-gray-600">₹{{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-3 text-right font-bold text-gray-800">₹{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Totals --}}
        <div class="px-6 py-4 flex justify-end">
            <div class="w-64 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>₹{{ number_format($order->subtotal, 2) }}</span></div>
                @if($order->discount > 0)
                <div class="flex justify-between text-green-600"><span>Discount</span><span>-₹{{ number_format($order->discount, 2) }}</span></div>
                @endif
                <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span>₹{{ number_format($order->delivery_charge ?? $order->shipping_cost ?? 0, 2) }}</span></div>
                <hr class="border-gray-100">
                <div class="flex justify-between font-bold text-gray-800 text-base">
                    <span>Grand Total</span>
                    <span>₹{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Shipping Address --}}
        @if($order->shipping_address)
        <div class="px-6 py-4 border-t bg-gray-50">
            <h2 class="font-semibold text-gray-700 mb-2 text-sm">Shipping Address</h2>
            @if(is_array($order->shipping_address))
                <p class="text-sm text-gray-600">{{ $order->shipping_address['name'] ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $order->shipping_address['address'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $order->shipping_address['state'] ?? '' }} — {{ $order->shipping_address['pincode'] ?? '' }}</p>
            @else
                <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
