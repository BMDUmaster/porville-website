@extends('layouts.dashboard')
@section('title', 'Order Detail')
@section('page_title', 'Order Details')

@section('content')
@php
    $statusOptions = ['pending', 'confirmed', 'processing', 'out_for_delivery', 'delivered', 'cancelled'];
    $allowedStatusOptions = function (string $currentStatus) {
        return match ($currentStatus) {
            'pending' => [
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'out_for_delivery' => 'Out For Delivery',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ],
            'confirmed' => [
                'processing' => 'Processing',
                'out_for_delivery' => 'Out For Delivery',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ],
            'processing' => [
                'confirmed' => 'Confirmed',
                'out_for_delivery' => 'Out For Delivery',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ],
            'out_for_delivery' => [
                'processing' => 'Processing',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ],
            'delivered' => [],
            'cancelled' => [],
            default => [
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'out_for_delivery' => 'Out For Delivery',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ],
        };
    };
    $pricingDayLabel = fn (?string $day) => $day === 'tomorrow' ? 'Tomorrow' : 'Today';
    $orderPricingDays = $order->items
        ->pluck('pricing_day')
        ->filter()
        ->unique()
        ->map($pricingDayLabel)
        ->values();
@endphp
<div class="p-4 md:p-6">
    <div class="mb-4 flex items-center justify-between print:hidden">
        <a href="{{ route('dashboard.orders') }}" class="flex items-center gap-1 text-sm text-amber-600 hover:underline">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" title="Print" class="flex h-9 w-9 items-center justify-center rounded bg-teal-600 text-sm text-white hover:bg-teal-700">
                <i class="fa-solid fa-print"></i>
            </button>
        </div>
    </div>

    <div class="mb-4 rounded-xl border bg-white p-5">
        <h2 class="mb-4 flex items-center gap-2 text-lg font-bold text-amber-600">
            <i class="fa-solid fa-circle-check text-amber-500"></i> Order Details
        </h2>

        <div class="overflow-hidden rounded-lg border border-gray-200">
            <div class="flex items-center justify-between border-b bg-gray-50 px-4 py-3">
                <div class="flex items-center gap-3">
                    <span class="rounded bg-gray-800 px-2 py-1 text-xs font-bold text-white">#1</span>
                    <span class="text-sm font-semibold text-gray-800">Order: {{ $order->order_number ?? 'ORD-' . $order->id }}</span>
                    <span class="rounded px-2.5 py-1 text-xs font-bold {{ $order->status_badge_class }}">
                        {{ $order->status_label }}
                    </span>
                    <span class="text-xs text-gray-500">
                        <i class="fa-solid fa-credit-card mr-1"></i>{{ ucfirst(str_replace('_', ' ', $order->payment_method ?? 'cod')) }}
                    </span>
                    {{-- Payment Status Badge --}}
                    @php
                        $paymentBadgeClass = match($order->payment_status) {
                            'paid'    => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'failed'  => 'bg-red-100 text-red-700 border-red-200',
                            default   => 'bg-amber-100 text-amber-700 border-amber-200',
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $paymentBadgeClass }}">
                        {{ ucfirst($order->payment_status ?? 'pending') }}
                    </span>
                </div>
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

            <div class="border-b bg-emerald-50 px-5 py-4">
                <p class="mb-2 flex items-center gap-1 text-xs font-black uppercase tracking-[0.14em] text-emerald-700">
                    <i class="fa-solid fa-truck-fast"></i> Delivery Schedule
                </p>
                <div class="flex flex-wrap gap-2">
                    @forelse($orderPricingDays as $dayLabel)
                        <span class="inline-flex items-center rounded-full bg-white px-3 py-1.5 text-xs font-black text-emerald-700 shadow-sm ring-1 ring-emerald-100">
                            <i class="fa-regular fa-calendar-check mr-1.5"></i>{{ $dayLabel }}
                        </span>
                    @empty
                        <span class="inline-flex items-center rounded-full bg-white px-3 py-1.5 text-xs font-black text-gray-500 shadow-sm ring-1 ring-gray-100">
                            Day not set
                        </span>
                    @endforelse
                    <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1.5 text-xs font-black text-amber-800">
                        <i class="fa-regular fa-clock mr-1.5"></i>{{ $order->delivery_slot_label ?? 'Slot not set' }}
                    </span>
                </div>
            </div>

            @if($order->razorpayPayments->isNotEmpty())
                <div class="border-b bg-[#faf7f0] px-5 py-4">
                    <p class="mb-3 flex items-center gap-1 text-xs font-black uppercase tracking-[0.14em] text-[#b8862c]">
                        <i class="fa-solid fa-credit-card"></i> Razorpay Payment
                    </p>
                    @foreach($order->razorpayPayments->sortByDesc('created_at') as $payment)
                        @php
                            $rzpBadgeClass = match($payment->status) {
                                'paid'      => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'failed'    => 'bg-red-100 text-red-700 border-red-200',
                                'cancelled' => 'bg-gray-100 text-gray-600 border-gray-200',
                                default     => 'bg-amber-100 text-amber-700 border-amber-200',
                            };
                        @endphp
                        <div class="mb-2 rounded-lg border border-amber-100 bg-white p-3 text-xs last:mb-0">
                            <div class="mb-1.5 flex items-center justify-between">
                                <span class="inline-flex items-center rounded-full border px-2.5 py-0.5 font-bold {{ $rzpBadgeClass }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                                <span class="text-gray-400">{{ $payment->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="grid grid-cols-1 gap-1 text-gray-600 sm:grid-cols-2">
                                <p><span class="font-semibold text-gray-800">Amount:</span> &#8377;{{ number_format($payment->amount / 100, 2) }}</p>
                                <p><span class="font-semibold text-gray-800">Razorpay Order ID:</span> {{ $payment->razorpay_order_id }}</p>
                                @if($payment->razorpay_payment_id)
                                    <p class="sm:col-span-2"><span class="font-semibold text-gray-800">Razorpay Payment ID:</span> {{ $payment->razorpay_payment_id }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

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

                    <div class="mt-4 rounded-lg border border-dashed border-amber-200 bg-amber-50 px-3 py-3">
                        <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Assigned Delivery Boy</p>
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
                                <th class="pb-2 pr-4 text-left text-xs font-bold uppercase text-gray-600">Delivery Day</th>
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
                                    <td class="py-3 pr-4">
                                        <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-700">
                                            {{ $item->pricing_day_label }}
                                        </span>
                                    </td>
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
                        @if((float) ($order->delivery_charge ?? $order->shipping_cost ?? 0) > 0)
                            <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                                <span class="font-semibold text-gray-700">Delivery</span>
                                <span class="text-gray-700">Rs{{ number_format($order->delivery_charge ?? $order->shipping_cost ?? 0, 2) }}</span>
                            </div>
                        @endif
                        @if((float) ($order->service_charge ?? 0) > 0)
                            <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                                <span class="font-semibold text-gray-700">
                                    &#8505;&#65039; Service Charge
                                    @if($order->service_charge_percent)
                                        ({{ rtrim(rtrim(number_format($order->service_charge_percent, 2), '0'), '.') }}%)
                                    @endif
                                </span>
                                <span class="text-gray-700">Rs{{ number_format($order->service_charge, 2) }}</span>
                            </div>
                        @endif
                        @if((float) ($order->admin_commission ?? 0) > 0)
                            <div class="flex justify-between border-b border-gray-100 py-2 text-sm">
                                <span class="font-semibold text-gray-700">Admin Commission</span>
                                <span class="text-gray-700">Rs{{ number_format($order->admin_commission ?? 0, 2) }}</span>
                            </div>
                        @endif
                        <div class="mt-1 flex justify-between rounded bg-green-50 px-3 py-2.5 text-sm">
                            <span class="font-bold text-gray-800">Grand Total</span>
                            <span class="font-bold text-gray-800">Rs{{ number_format($order->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $detailAllowedOptions = $allowedStatusOptions($order->status);
            @endphp
            @if(count($detailAllowedOptions) > 0)
                <div class="bg-gray-50 px-5 py-4 print:hidden border-t">
                    <form method="POST" action="{{ route('dashboard.orders.status', $order) }}" class="flex flex-wrap items-center gap-3">
                        @csrf
                        @method('PATCH')
                        <label class="text-sm font-semibold text-gray-700">Update Status:</label>
                        <select name="status" id="detailOrderStatus" onchange="toggleDetailDeliveryBoy()" class="rounded border border-gray-300 px-3 py-2 text-sm font-semibold outline-none focus:border-amber-400 bg-white">
                            <option value="" disabled selected>Change Status...</option>
                            @foreach($detailAllowedOptions as $val => $label)
                                <option value="{{ $val }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <select name="delivery_boy_id" id="detailDeliveryBoy" class="rounded border border-gray-300 px-3 py-2 text-sm outline-none focus:border-amber-400 hidden">
                            <option value="">Choose delivery boy</option>
                            @foreach($deliveryBoys as $deliveryBoy)
                                <option value="{{ $deliveryBoy->id }}" {{ $order->delivery_boy_id === $deliveryBoy->id ? 'selected' : '' }}>
                                    {{ $deliveryBoy->partner_name }} | {{ $deliveryBoy->phone_number }} | {{ $deliveryBoy->area }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="rounded bg-amber-600 px-5 py-2 text-sm font-bold text-white hover:bg-amber-700">
                            Update
                        </button>
                    </form>
                </div>
            @endif
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
