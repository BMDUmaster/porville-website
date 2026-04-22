@extends('frontend.layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    {{-- Hero --}}
    <div class="bg-gradient-to-r from-green-800 to-blue-700 rounded-2xl p-8 text-white mb-6 flex items-center gap-6">
        <div class="w-20 h-20 rounded-full bg-white/20 flex items-center justify-center text-3xl font-extrabold flex-shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-2xl font-extrabold">{{ $user->name }}</h1>
            <p class="text-white/70 text-sm mt-1">{{ $user->email }}</p>
            @if($user->phone)
                <p class="text-white/70 text-sm">{{ $user->phone }}</p>
            @endif
        </div>
        <div class="ml-auto flex gap-3">
            <a href="{{ route('frontend.orders') }}" class="bg-white/15 hover:bg-white/25 text-white font-bold text-sm px-4 py-2 rounded-lg transition">
                My Orders
            </a>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Update Profile --}}
        <div class="bg-white rounded-2xl border p-6">
            <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user-circle text-blue-600"></i> Personal Information
            </h2>
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('frontend.profile.update') }}" class="space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Phone</label>
                    <input type="tel" name="phone" value="{{ $user->phone }}"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email (read-only)</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full border border-gray-100 rounded-xl px-4 py-2.5 text-sm bg-gray-50 text-gray-400">
                </div>
                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl text-sm transition">
                    Update Profile
                </button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="bg-white rounded-2xl border p-6">
            <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-lock text-red-500"></i> Change Password
            </h2>
            <form method="POST" action="{{ route('frontend.profile.password') }}" class="space-y-4">
                @csrf @method('PUT')
                @error('current_password')
                    <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-600">{{ $message }}</div>
                @enderror
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Current Password</label>
                    <input type="password" name="current_password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">New Password</label>
                    <input type="password" name="password" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-2.5 rounded-xl text-sm transition">
                    Update Password
                </button>
            </form>
        </div>
    </div>

    {{-- Recent Orders --}}
    @if($orders->count())
    <div class="bg-white rounded-2xl border p-6 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-green-600"></i> Recent Orders
            </h2>
            <a href="{{ route('frontend.orders') }}" class="text-blue-600 text-xs font-semibold hover:underline">View All</a>
        </div>
        @foreach($orders as $order)
        <div class="flex items-center justify-between py-3 border-b last:border-b-0">
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $order->order_number ?? '#'.$order->id }}</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }} · {{ $order->items->count() }} item(s)</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-blue-700 text-sm">₹{{ number_format($order->total, 2) }}</p>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full {{ $order->status_badge_class }}">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
