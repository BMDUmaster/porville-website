@extends('layouts.dashboard')
@section('title', 'Total Orders')
@section('page_title', 'Total Orders')

@section('content')
<div class="p-4 sm:p-6 max-w-7xl">

    {{-- Category Weight Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5 mb-8">
        @php
            $catCards = [
                ['Fish','Fish','blue'],
                ['Chicken','Chicken','yellow'],
                ['Mutton','Mutton','red'],
                ['Veg','Veg','green'],
                ['Fruits','Fruits','purple'],
            ];
        @endphp
        @foreach($catCards as [$cat,$label,$color])
        <div class="bg-white rounded-xl border p-4 flex items-center justify-between hover:-translate-y-1 hover:shadow-lg transition">
            <div>
                <p class="text-xs text-gray-500">{{ $label }}</p>
                <p class="text-xl font-bold text-{{ $color }}-600">
                    {{ collect($byCategory)->get($cat, ['quantity' => 0])['quantity'] ?? 0 }} kg
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-{{ $color }}-100 flex items-center justify-center text-sm font-bold text-{{ $color }}-700">{{ substr($label, 0, 1) }}</div>
        </div>
        @endforeach
    </div>

    {{-- Summary --}}
    <div class="flex flex-wrap justify-center gap-6 mb-8">
        <div class="bg-white rounded-xl border p-5 flex items-center justify-between w-64 hover:-translate-y-1 hover:shadow-lg transition">
            <div>
                <p class="text-xs text-gray-500">Total Orders</p>
                <p class="text-2xl font-bold">{{ $orders->count() }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-base font-bold text-amber-700">OD</div>
        </div>
        <div class="bg-white rounded-xl border p-5 flex items-center justify-between w-64 hover:-translate-y-1 hover:shadow-lg transition">
            <div>
                <p class="text-xs text-gray-500">Total Weight</p>
                <p class="text-2xl font-bold text-amber-600">
                    {{ collect($byCategory)->sum(fn($c) => $c['quantity']) }} kg
                </p>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-base font-bold text-amber-700">KG</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full min-w-[920px]">
            <thead style="background: linear-gradient(to right, #0ea5e9, #2563eb); color: white;">
                <tr>
                    <th class="px-4 py-3 text-left text-sm">Order ID</th>
                    <th class="px-4 py-3 text-left text-sm">Customer</th>
                    <th class="px-4 py-3 text-left text-sm">Category</th>
                    <th class="px-4 py-3 text-left text-sm">Items Total</th>
                    <th class="px-4 py-3 text-left text-sm">Quantity</th>
                    <th class="px-4 py-3 text-left text-sm">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 align-top">
                    <td class="px-4 py-3 font-bold text-amber-600">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3">{{ $order->user->name ?? 'Guest' }}</td>
                    <td class="px-4 py-3">{{ $order->items->first()?->product?->category?->name ?? '-' }}</td>
                    <td class="px-4 py-3">
                        <div class="space-y-1">
                            @forelse($order->items as $item)
                                <div class="text-xs text-gray-700">
                                    <span class="font-semibold text-gray-800">{{ $item->product->name ?? 'Product' }}</span>
                                    <span class="text-gray-400">-</span>
                                    <span class="font-bold text-emerald-600">Rs{{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            @empty
                                <span class="text-xs text-gray-400">No items</span>
                            @endforelse
                        </div>
                    </td>
                    <td class="px-4 py-3 font-bold text-amber-600">{{ $order->items->sum('quantity') }} kg</td>
                    <td class="px-4 py-3 font-bold">Rs{{ number_format($order->total, 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
