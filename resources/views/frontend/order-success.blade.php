@extends('frontend.layouts.app')
@section('title', 'Order Placed!')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="rounded-3xl border bg-white p-10 shadow-sm">
        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
            <i class="fa-solid fa-check text-3xl text-green-600"></i>
        </div>
        <h1 class="nunito mb-2 text-2xl font-extrabold text-gray-800">Order Placed!</h1>
        <p class="mb-6 text-sm text-gray-500">Thank you for your order. We'll start processing it right away.</p>

        <div class="mb-6 space-y-2 rounded-xl bg-gray-50 p-4 text-left">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Order Number</span>
                <span class="font-bold text-blue-700">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Status</span>
                <span class="font-bold text-green-600">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Total</span>
                <span class="font-bold text-gray-800">&#8377;{{ number_format($order->total, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Payment</span>
                <span class="font-bold uppercase text-gray-800">{{ $order->payment_method }}</span>
            </div>
            @if($order->delivery_slot_label)
            <div class="flex justify-between gap-4 text-sm">
                <span class="text-gray-500">Delivery Slot</span>
                <span class="text-right font-bold text-amber-700">{{ $order->delivery_slot_label }}</span>
            </div>
            @endif
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('frontend.order.show', $order->id) }}"
               class="w-full rounded-xl bg-blue-700 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                View Order Details
            </a>
            <a href="{{ route('frontend.order.invoice', ['id' => $order->id, 'download' => 1]) }}"
               target="_blank"
               class="w-full rounded-xl bg-[#0f766e] py-3 text-sm font-bold text-white transition hover:bg-[#0d665f]">
                Download Invoice
            </a>
            <a href="{{ route('frontend.products') }}"
               class="w-full rounded-xl border border-gray-200 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
