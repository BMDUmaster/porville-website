@extends('layouts.dashboard')
@section('title', 'Order Detail')
@section('page_title', 'Order Details')

@section('content')
@php
    $statusOptions = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
@endphp
<div class="p-4 md:p-6">
    <div class="mb-4 flex items-center justify-between">
        <a href="{{ route('dashboard.orders') }}" class="flex items-center gap-1 text-sm text-blue-600 hover:underline">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back
        </a>
        <div class="flex items-center gap-2">
            <a href="#" title="Export Excel" class="flex h-9 w-9 items-center justify-center rounded bg-green-600 text-sm text-white hover:bg-green-700">
                <i class="fa-solid fa-file-excel"></i>
            </a>
            <a href="#" title="Export PDF" class="flex h-9 w-9 items-center justify-center rounded bg-red-600 text-sm text-white hover:bg-red-700">
                <i class="fa-solid fa-file-pdf"></i>
            </a>
            <button onclick="window.print()" title="Print" class="flex h-9 w-9 items-center justify-center rounded bg-teal-600 text-sm text-white hover:bg-teal-700">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
    </div>

    <div class="mb-4 rounded-xl border bg-white p-5">
        <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-blue-600">
            <i class="fa-solid fa-circle-check text-blue-500"></i> Order Details
        </h2>

        <div class="mb-4 flex justify-end">
            <div class="flex items-center gap-2 text-sm">
                <span class="font-medium text-gray-600">Search:</span>
                <input type="text" id="orderSearch" class="w-48 rounded border border-gray-300 px-3 py-1.5 text-sm outline-none focus:border-blue-400">
            </div>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200">
            <div class="flex items-center justify-between border-b bg-gray-50 px-4 py-3">
                <div class="flex items-center gap-3">
                    <span class="rounded bg-gray-800 px-2 py-1 text-xs font-bold text-white">#1</span>
                    <span class="text-sm font-semibold text-gray-800">Order: {{ $order->order_number ?? 'ORD-' . $order->id }}</span>
                    <span class="rounded px-2.5 py-1 text-xs font-bold {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                </div>
                <a href="#" onclick="window.print()" class="flex items-center gap-1.5 rounded border border-red-300 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">
                    <i class="fa-solid fa-download text-xs"></i> Download PDF
                </a>
            </div>

            <div class="border-b bg-white px-4 py-2">
                <span class="text-xs text-gray-500">
                    <i class="fa-regular fa-calendar mr-1"></i>
                    {{ $order->created_at->format('d M Y, h:i A') }}
                </span>
                @if($order->delivery_slot_label)
                    <span class="ml-4 text-xs font-semibold text-amber-700">
                        <i class="fa-regular fa-clock mr-1"></i>
                        {{ $order->delivery_slot_label }}
                    </span>
                @endif
            </div>

            <div class="border-b">
                <div class="px-5 py-4">
                    <p class="mb-2 flex items-center gap-1 text-xs font-bold text-teal-600">
                        <i class="fa-regular fa-user"></i> Customer
                    </p>
                    <p class="text-sm font-bold text-gray-800">{{ $order->user->name ?? 'Guest' }}</p>
                    @if($order->user->phone ?? null)
                        <p class="mt-1 flex items-center gap-1 text-xs text-gray-600">
                            <i class="fa-solid fa-phone text-gray-400"></i> {{ $order->user->phone }}
                        </p>
                    @endif
                    @if($order->shipping_address)
                        <p class="mt-1 flex items-start gap-1 text-xs text-gray-500">
                            <i class="fa-solid fa-location-dot mt-0.5 text-gray-400"></i>
                            <span>
                                @if(is_array($order->shipping_address))
                                    {{ implode(', ', array_filter($order->shipping_address)) }}
                                @else
                                    {{ $order->shipping_address }}
                                @endif
                            </span>
                        </p>
                    @endif

                    <div class="mt-4 rounded-lg border border-dashed border-blue-200 bg-blue-50 px-3 py-3">
                        <p class="text-xs font-bold uppercase tracking-wide text-blue-700">Assigned Delivery Boy</p>
                        @if($order->deliveryBoy)
                            <p class="mt-2 text-sm font-bold text-gray-800">{{ $order->deliveryBoy->partner_name }}</p>
                            <p class="mt-1 text-xs text-gray-600">{{ $order->deliveryBoy->phone_number }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $order->deliveryBoy->area }}</p>
                        @else
                            <p class="mt-2 text-xs text-gray-500">No delivery boy assigned yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="border-b bg-white px-5 py-3">
                <p class="mb-3 flex items-center gap-1 text-xs font-bold text-teal-600">
                    <i class="fa-solid fa-cart-shopping"></i> Products
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px] text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Image</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Product</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">QTY</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Unit</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">MRP</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Selling</th>
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Save Offer</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3 pr-4">
                                        @php $imgs = $item->product->images ?? []; @endphp
                                        @if(count($imgs))
                                            <img src="{{ asset('storage/' . $imgs[0]) }}" class="h-12 w-12 rounded border object-cover">
                                        @else
                                            <div class="flex h-12 w-12 items-center justify-center rounded border bg-gray-100 text-gray-400">
                                                <i class="fa-regular fa-image text-xs"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 pr-4 text-sm font-medium text-gray-800">{{ $item->product->name ?? 'Deleted Product' }}</td>
                                    <td class="py-3 pr-4 text-sm text-gray-700">{{ $item->quantity }}</td>
                                    <td class="py-3 pr-4 text-sm text-gray-700">{{ $item->unit ?? $item->product->unit ?? '-' }}</td>
                                    <td class="py-3 pr-4 text-sm text-gray-700">Rs{{ number_format($item->mrp ?? $item->unit_price, 2) }}</td>
                                    <td class="py-3 pr-4 text-sm text-gray-700">Rs{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-3 pr-4">
                                        @if($item->save_offer)
                                            <span class="rounded bg-amber-400 px-2 py-0.5 text-xs font-bold text-white">{{ $item->save_offer }}%</span>
                                        @else
                                            <span class="text-sm text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <div class="w-full max-w-sm">
                        <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                            <span class="font-semibold text-gray-700">Delivery</span>
                            <span class="text-gray-700">Rs{{ number_format($order->delivery_charge ?? $order->shipping_cost ?? 0, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                            <span class="font-semibold text-gray-700">
                                &#8505;&#65039; Service Charge
                                @if($order->service_charge_percent)
                                    ({{ rtrim(rtrim(number_format($order->service_charge_percent, 2), '0'), '.') }}%)
                                @endif
                            </span>
                            <span class="text-gray-700">Rs{{ number_format($order->service_charge, 2) }}</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                            <span class="font-semibold text-gray-700">Admin Commission</span>
                            <span class="text-gray-700">Rs{{ number_format($order->admin_commission ?? 0, 2) }}</span>
                        </div>
                        <div class="mt-1 flex justify-between rounded bg-green-50 px-3 py-2.5 text-sm">
                            <span class="font-bold text-gray-800">Grand Total</span>
                            <span class="font-bold text-gray-800">Rs{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 px-5 py-4">
                <form method="POST" action="{{ route('dashboard.orders.status', $order) }}" class="flex flex-wrap items-center gap-3">
                    @csrf
                    @method('PATCH')
                    <label class="text-sm font-semibold text-gray-700">Update Status:</label>
                    <select name="status" id="detailOrderStatus" onchange="toggleDetailDeliveryBoy()" class="rounded border border-gray-300 px-3 py-2 text-sm outline-none focus:border-blue-400">
                        @foreach($statusOptions as $status)
                            <option value="{{ $status }}" {{ $order->status === $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="delivery_boy_id" id="detailDeliveryBoy" class="rounded border border-gray-300 px-3 py-2 text-sm outline-none focus:border-blue-400 {{ $order->status === 'out_for_delivery' ? '' : 'hidden' }}">
                        <option value="">Choose delivery boy</option>
                        @foreach($deliveryBoys as $deliveryBoy)
                            <option value="{{ $deliveryBoy->id }}" {{ $order->delivery_boy_id === $deliveryBoy->id ? 'selected' : '' }}>
                                {{ $deliveryBoy->partner_name }} | {{ $deliveryBoy->phone_number }} | {{ $deliveryBoy->area }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded bg-blue-600 px-5 py-2 text-sm font-bold text-white hover:bg-blue-700">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleDetailDeliveryBoy() {
    const statusSelect = document.getElementById('detailOrderStatus');
    const deliveryBoySelect = document.getElementById('detailDeliveryBoy');

    if (!statusSelect || !deliveryBoySelect) {
        return;
    }

    const shouldShow = statusSelect.value === 'out_for_delivery';
    deliveryBoySelect.classList.toggle('hidden', !shouldShow);

    if (!shouldShow) {
        deliveryBoySelect.value = '';
    }
}
</script>
@endsection
