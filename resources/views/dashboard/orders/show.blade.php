@extends('layouts.dashboard')
@section('title', 'Order Detail')
@section('page_title', 'Order Details')

@section('content')
    <div class="p-4 md:p-6">

        {{-- Top action bar --}}
        <div class="flex items-center justify-between mb-4">
            <a href="{{ route('dashboard.orders') }}" class="text-sm text-blue-600 hover:underline flex items-center gap-1">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back
            </a>
            <div class="flex items-center gap-2">
                {{-- Excel --}}
                <a href="#" title="Export Excel"
                    class="w-9 h-9 flex items-center justify-center bg-green-600 hover:bg-green-700 text-white rounded text-sm">
                    <i class="fa-solid fa-file-excel"></i>
                </a>
                {{-- PDF --}}
                <a href="#" title="Export PDF"
                    class="w-9 h-9 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                    <i class="fa-solid fa-file-pdf"></i>
                </a>
                {{-- Print --}}
                <button onclick="window.print()" title="Print"
                    class="w-9 h-9 flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white rounded text-sm">
                    <i class="fa-solid fa-print"></i>
                </button>
            </div>
        </div>

        {{-- Completed Orders heading --}}
        <div class="bg-white rounded-xl border p-5 mb-4">
            <h2 class="text-lg font-bold text-blue-600 flex items-center gap-2 mb-4">
                <i class="fa-solid fa-circle-check text-blue-500"></i> Order Details
            </h2>

            {{-- Search bar --}}
            <div class="flex justify-end mb-4">
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-600 font-medium">Search:</span>
                    <input type="text" id="orderSearch" placeholder=""
                        class="border border-gray-300 rounded px-3 py-1.5 text-sm outline-none focus:border-blue-400 w-48">
                </div>
            </div>

            {{-- Order Card --}}
            <div class="border border-gray-200 rounded-lg overflow-hidden">

                {{-- Order header --}}
                <div class="flex items-center justify-between px-4 py-3 bg-gray-50 border-b">
                    <div class="flex items-center gap-3">
                        <span class="bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">#1</span>
                        <span class="text-sm font-semibold text-gray-800">
                            Order: {{ $order->order_number ?? 'ORD-' . $order->id }}
                        </span>
                        @php
                            $statusColors = [
                                'pending' => 'bg-amber-100 text-amber-700',
                                'confirmed' => 'bg-blue-100 text-blue-700',
                                'processing' => 'bg-yellow-100 text-yellow-700',
                                'shipped' => 'bg-indigo-100 text-indigo-700',
                                'delivered' => 'bg-emerald-100 text-emerald-700 border border-emerald-300',
                                'cancelled' => 'bg-red-100 text-red-700',
                            ];
                            $sc = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="text-xs font-bold px-2.5 py-1 rounded {{ $sc }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <a href="#" onclick="window.print()"
                        class="flex items-center gap-1.5 text-xs font-semibold text-red-600 border border-red-300 px-3 py-1.5 rounded hover:bg-red-50">
                        <i class="fa-solid fa-download text-xs"></i> Download PDF
                    </a>
                </div>

                {{-- Date --}}
                <div class="px-4 py-2 border-b bg-white">
                    <span class="text-xs text-gray-500">
                        <i class="fa-regular fa-calendar mr-1"></i>
                        {{ $order->created_at->format('d M Y, h:i A') }}
                    </span>
                </div>

                {{-- User --}}
                <div class="border-b">

                    {{-- User --}}
                    <div class="px-5 py-4">
                        <p class="text-xs font-bold text-teal-600 flex items-center gap-1 mb-2">
                            <i class="fa-regular fa-user"></i> User
                        </p>
                        <p class="font-bold text-sm text-gray-800">{{ $order->user->name ?? 'Guest' }}</p>
                        @if($order->user->phone ?? null)
                            <p class="text-xs text-gray-600 flex items-center gap-1 mt-1">
                                <i class="fa-solid fa-phone text-gray-400"></i> {{ $order->user->phone }}
                            </p>
                        @endif
                        @if($order->shipping_address)
                            <p class="text-xs text-gray-500 flex items-start gap-1 mt-1">
                                <i class="fa-solid fa-location-dot text-gray-400 mt-0.5"></i>
                                <span>
                                    @if(is_array($order->shipping_address))
                                        {{ implode(', ', array_filter($order->shipping_address)) }}
                                    @else
                                        {{ $order->shipping_address }}
                                    @endif
                                </span>
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Products section --}}
                <div class="px-5 py-3 border-b bg-white">
                    <p class="text-xs font-bold text-teal-600 flex items-center gap-1 mb-3">
                        <i class="fa-solid fa-cart-shopping"></i> Products
                    </p>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm min-w-[700px]">
                            <thead>
                                <tr class="border-b border-gray-200">
                                    <th class="pb-2 text-left text-xs font-bold text-gray-600 uppercase pr-4">Image</th>
                                    <th class="pb-2 text-left text-xs font-bold text-gray-600 uppercase pr-4">Product</th>
                                    <th class="pb-2 text-left text-xs font-bold text-gray-600 uppercase pr-4">QTY</th>
                                    <th class="pb-2 text-left text-xs font-bold text-gray-600 uppercase pr-4">Unit</th>
                                    <th class="pb-2 text-left text-xs font-bold text-gray-600 uppercase pr-4">MRP</th>
                                    <th class="pb-2 text-left text-xs font-bold text-gray-600 uppercase pr-4">Selling</th>
                                    <th class="pb-2 text-left text-xs font-bold text-gray-600 uppercase pr-4">Save Offer
                                    </th>

                                    <th class="pb-2 text-left text-xs font-bold text-gray-600 uppercase">Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="py-3 pr-4">
                                            @php $imgs = $item->product->images ?? []; @endphp
                                            @if(count($imgs))
                                                <img src="{{ asset('storage/' . $imgs[0]) }}"
                                                    class="w-12 h-12 rounded object-cover border">
                                            @else
                                                <div
                                                    class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center text-gray-400 border">
                                                    <i class="fa-regular fa-image text-xs"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-4 font-medium text-gray-800 text-sm">
                                            {{ $item->product->name ?? 'Deleted Product' }}
                                        </td>
                                        <td class="py-3 pr-4 text-sm text-gray-700">{{ $item->quantity }}</td>
                                        <td class="py-3 pr-4 text-sm text-gray-700">
                                            {{ $item->unit ?? $item->product->unit ?? '—' }}</td>
                                        <td class="py-3 pr-4 text-sm text-gray-700">
                                            ₹{{ number_format($item->mrp ?? $item->unit_price, 2) }}
                                        </td>
                                        <td class="py-3 pr-4 text-sm text-gray-700">
                                            ₹{{ number_format($item->unit_price, 2) }}
                                        </td>
                                        <td class="py-3 pr-4">
                                            @if($item->save_offer)
                                                <span class="bg-amber-400 text-white text-xs font-bold px-2 py-0.5 rounded">
                                                    {{ $item->save_offer }}%
                                                </span>
                                            @else
                                                <span class="text-sm text-gray-400">—</span>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-4 text-sm text-gray-700">
                                            ₹{{ number_format($item->vendor_amount ?? 0, 2) }}
                                        </td>
                                        <td class="py-3 text-sm text-gray-700">
                                            ₹{{ number_format($item->admin_amount ?? 0, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Totals summary --}}
                    <div class="mt-4 flex justify-end">
                        <div class="w-full max-w-sm">
                            <div class="flex justify-between py-2 border-b border-gray-100 text-sm">
                                <span class="font-semibold text-gray-700">Delivery</span>
                                <span class="text-gray-700">
                                    ₹{{ number_format($order->delivery_charge ?? $order->shipping_cost ?? 0, 2) }}
                                </span>
                            </div>

                            <div class="flex justify-between py-2 border-b border-gray-100 text-sm">
                                <span class="font-semibold text-gray-700">Platform</span>
                                <span class="text-gray-700">
                                    ₹{{ number_format($order->platform_fee ?? 0, 2) }}
                                </span>
                            </div>

                            <div class="flex justify-between py-2 border-b border-gray-100 text-sm">
                                <span class="font-semibold text-gray-700">Admin Commission</span>
                                <span class="text-gray-700">
                                    ₹{{ number_format($order->admin_commission ?? 0, 2) }}
                                </span>
                            </div>

                            <div class="flex justify-between py-2.5 bg-green-50 px-3 rounded mt-1 text-sm">
                                <span class="font-bold text-gray-800">Grand Total</span>
                                <span class="font-bold text-gray-800">
                                    ₹{{ number_format($order->total, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Update Status --}}
                <div class="px-5 py-4 bg-gray-50">
                    <form method="POST" action="{{ route('dashboard.orders.status', $order) }}"
                        class="flex flex-wrap gap-3 items-center">
                        @csrf @method('PATCH')
                        <label class="text-sm font-semibold text-gray-700">Update Status:</label>
                        <select name="status"
                            class="border border-gray-300 rounded px-3 py-2 text-sm outline-none focus:border-blue-400">
                            @foreach(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded text-sm font-bold">
                            Update
                        </button>
                    </form>
                </div>

            </div>{{-- end order card --}}
        </div>
    </div>
@endsection