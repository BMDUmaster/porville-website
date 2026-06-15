@extends('frontend.layouts.app')
@section('title', 'Order Details')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('frontend.orders') }}" class="flex items-center gap-1 text-sm text-blue-600 hover:underline">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back to Orders
        </a>
        <a href="{{ route('frontend.order.invoice', ['id' => $order->id, 'download' => 1]) }}"
           target="_blank"
           class="inline-flex items-center gap-2 rounded-lg bg-[#0f766e] px-3 py-2 text-xs font-bold text-white transition hover:bg-[#0d665f]">
            <i class="fa-solid fa-file-arrow-down text-xs"></i> Download Invoice
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl border bg-white">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <div>
                <h1 class="font-bold text-gray-800">{{ $order->order_number ?? '#'.$order->id }}</h1>
                <p class="mt-1 text-xs text-gray-400">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                @if($order->delivery_slot_label)
                <p class="mt-2 inline-flex items-center gap-2 rounded-full bg-amber-50 px-3 py-1 text-[11px] font-semibold text-amber-700">
                    <i class="fa-regular fa-clock"></i>
                    Delivery slot: {{ $order->delivery_slot_label }}
                </p>
                @endif
            </div>
            <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $order->status_badge_class }}">
                {{ $order->status_label }}
            </span>
        </div>

        <div class="border-b px-6 py-4">
            <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-700">
                <i class="fa-solid fa-cart-shopping text-teal-600"></i> Products
            </h2>
            <table class="w-full text-sm">
                <thead class="border-b">
                    <tr class="text-left text-xs font-bold uppercase text-gray-500">
                        <th class="pb-2">Product</th>
                        <th class="pb-2">QTY</th>
                        <th class="pb-2">Unit Price</th>
                        <th class="pb-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <tr>
                        <td class="py-3 font-medium text-gray-800">{{ $item->product->name ?? 'Deleted Product' }}</td>
                        <td class="py-3 text-gray-600">
                            {{ $item->quantity }}
                            @if($item->variant_label)
                                <span class="text-xs text-gray-400">({{ $item->variant_label }})</span>
                            @endif
                        </td>
                        <td class="py-3 text-gray-600">&#8377;{{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-3 text-right font-bold text-gray-800">&#8377;{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end px-6 py-4">
            <div class="w-64 space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>&#8377;{{ number_format($order->subtotal, 2) }}</span></div>
                @if($order->discount > 0)
                <div class="flex justify-between text-green-600"><span>Discount</span><span>-&#8377;{{ number_format($order->discount, 2) }}</span></div>
                @endif
                @if((float) ($order->delivery_charge ?? $order->shipping_cost ?? 0) > 0)
                    <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span>&#8377;{{ number_format($order->delivery_charge ?? $order->shipping_cost ?? 0, 2) }}</span></div>
                @endif
                @if((float) ($order->service_charge ?? 0) > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">
                        &#8505;&#65039; Service Charge
                        @if($order->service_charge_percent)
                            ({{ rtrim(rtrim(number_format($order->service_charge_percent, 2), '0'), '.') }}%)
                        @endif
                    </span>
                    <span>&#8377;{{ number_format($order->service_charge, 2) }}</span>
                </div>
                @endif
                <hr class="border-gray-100">
                <div class="flex justify-between text-base font-bold text-gray-800">
                    <span>Grand Total</span>
                    <span>&#8377;{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        @if($order->shipping_address)
        <div class="border-t bg-gray-50 px-6 py-4">
            <h2 class="mb-2 text-sm font-semibold text-gray-700">Shipping Address</h2>
            @if(is_array($order->shipping_address))
                <p class="text-sm text-gray-600">{{ $order->shipping_address['name'] ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $order->shipping_address['address'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}</p>
                <p class="text-sm text-gray-600">{{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['pincode'] ?? '' }}</p>
            @else
                <p class="text-sm text-gray-600">{{ $order->shipping_address }}</p>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
