@extends('frontend.layouts.app')
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">Checkout</h1>

    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Left: Form --}}
        <div class="flex-1 space-y-5">
            <form method="POST" action="{{ route('frontend.checkout.store') }}" id="checkoutForm">
                @csrf

                @if($errors->any())
                <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-600">
                    @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                </div>
                @endif

                {{-- Contact --}}
                <div class="bg-white rounded-2xl border overflow-hidden">
                    <div class="px-6 py-4 border-b flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">1</div>
                        <h2 class="nunito font-extrabold text-base text-gray-800">Contact Information</h2>
                    </div>
                    <div class="px-6 py-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name', $checkoutDefaults['first_name'] ?? '') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Last Name *</label>
                            <input type="text" name="last_name" value="{{ old('last_name', $checkoutDefaults['last_name'] ?? '') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $checkoutDefaults['email'] ?? '') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Phone *</label>
                            <input type="tel" name="phone" value="{{ old('phone', $checkoutDefaults['phone'] ?? '') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Shipping --}}
                <div class="bg-white rounded-2xl border overflow-hidden">
                    <div class="px-6 py-4 border-b flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">2</div>
                        <h2 class="nunito font-extrabold text-base text-gray-800">Shipping Address</h2>
                    </div>
                    <div class="px-6 py-5 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Street Address *</label>
                            <input type="text" name="address" value="{{ old('address', $checkoutDefaults['address'] ?? '') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">City *</label>
                                <input type="text" name="city" value="{{ old('city', $checkoutDefaults['city'] ?? '') }}" required
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">State *</label>
                                <input type="text" name="state" value="{{ old('state', $checkoutDefaults['state'] ?? '') }}" required
                                       class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">PIN Code *</label>
                            <input type="text" name="pincode" value="{{ old('pincode', $checkoutDefaults['pincode'] ?? '') }}" required
                                   class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Payment --}}
                <div class="bg-white rounded-2xl border overflow-hidden">
                    <div class="px-6 py-4 border-b flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-blue-700 text-white text-xs font-bold flex items-center justify-center">3</div>
                        <h2 class="nunito font-extrabold text-base text-gray-800">Payment Method</h2>
                    </div>
                    <div class="px-6 py-5 space-y-3">
                        @foreach(['COD' => 'Cash on Delivery', 'online' => 'Online Payment', 'upi' => 'UPI'] as $val => $label)
                        <label class="flex items-center gap-3 border border-gray-200 rounded-xl p-4 cursor-pointer hover:border-blue-500 transition">
                            <input type="radio" name="payment_method" value="{{ $val }}" {{ $val === 'COD' ? 'checked' : '' }} class="accent-blue-600">
                            <span class="text-sm font-semibold text-gray-700">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Coupon --}}
                <div class="bg-white rounded-2xl border p-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-2">Coupon Code (optional)</label>
                    <input type="text" name="coupon_code" value="{{ old('coupon_code') }}" placeholder="Enter coupon code"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>

                <button type="submit"
                        class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-4 rounded-2xl text-sm transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-lock text-xs"></i>
                    Place Order — ₹{{ number_format($subtotal + 37, 2) }}
                </button>
            </form>
        </div>

        {{-- Right: Summary --}}
        <div class="w-full lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-2xl border overflow-hidden sticky top-24">
                <div class="h-1 bg-gradient-to-r from-blue-700 to-orange-400"></div>
                <div class="px-5 py-4 border-b">
                    <h2 class="nunito font-extrabold text-base text-gray-800">Order Summary</h2>
                </div>
                <div class="px-5 py-4 space-y-3 border-b max-h-64 overflow-y-auto">
                    @foreach($items as $item)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden">
                            @if($item['image'])
                                <img src="{{ asset('storage/'.$item['image']) }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-lg">🥩</span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $item['name'] }}</p>
                            <p class="text-[10px] text-gray-400">Qty: {{ $item['quantity'] }}</p>
                        </div>
                        <span class="text-xs font-bold text-gray-800 flex-shrink-0">₹{{ number_format($item['subtotal'], 2) }}</span>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 py-4 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span>₹{{ number_format($subtotal, 2) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span>₹25.00</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Platform Fee</span><span>₹12.00</span></div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between font-bold text-gray-800 text-base">
                        <span>Total</span>
                        <span class="text-blue-700">₹{{ number_format($subtotal + 37, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
