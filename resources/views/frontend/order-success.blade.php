@extends('frontend.layouts.app')
@section('title', 'Order Placed!')

@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-3xl border p-10 shadow-sm">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-check text-green-600 text-3xl"></i>
        </div>
        <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-2">Order Placed!</h1>
        <p class="text-gray-500 text-sm mb-6">Thank you for your order. We'll start processing it right away.</p>

        <div class="bg-gray-50 rounded-xl p-4 mb-6 text-left space-y-2">
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
                <span class="font-bold text-gray-800">₹{{ number_format($order->total, 2) }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">Payment</span>
                <span class="font-bold text-gray-800 uppercase">{{ $order->payment_method }}</span>
            </div>
        </div>

        <div class="flex flex-col gap-3">
            <a href="{{ route('frontend.order.show', $order->id) }}"
               class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3 rounded-xl text-sm transition">
                View Order Details
            </a>
            <a href="{{ route('frontend.products') }}"
               class="w-full border border-gray-200 text-gray-700 font-semibold py-3 rounded-xl text-sm hover:bg-gray-50 transition">
                Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection
