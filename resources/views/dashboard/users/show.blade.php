@extends('layouts.dashboard')
@section('title', 'User Details')
@section('page_title', 'User Details')

@section('content')
<div class="p-4 md:p-8 max-w-3xl">

    <a href="{{ route('dashboard.users') }}" class="text-blue-600 hover:underline text-sm flex items-center gap-1 mb-6">
        <i class="fa-solid fa-arrow-left"></i> Back to Users
    </a>

    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <span class="text-xs px-2 py-1 rounded-full font-bold {{ $user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                    {{ ucfirst($user->status ?? 'active') }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><p class="text-xs text-gray-500 mb-1">Phone</p><p class="font-bold">{{ $user->phone ?? '—' }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Role</p><p class="font-bold capitalize">{{ $user->role }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Joined</p><p class="font-bold">{{ $user->created_at->format('d M Y') }}</p></div>
            <div><p class="text-xs text-gray-500 mb-1">Total Orders</p><p class="font-bold text-indigo-600">{{ $user->orders->count() }}</p></div>
        </div>

        <div class="mt-6 flex gap-3">
            <form method="POST" action="{{ route('dashboard.users.toggle', $user) }}">
                @csrf @method('PATCH')
                <button type="submit"
                        class="px-5 py-2 rounded-lg text-sm font-bold {{ $user->status === 'active' ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                    {{ $user->status === 'active' ? 'Block User' : 'Unblock User' }}
                </button>
            </form>
        </div>
    </div>

    {{-- Orders --}}
    <div class="bg-white rounded-2xl shadow overflow-x-auto">
        <div class="px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Order History</h3>
        </div>
        <table class="w-full text-sm min-w-[500px]">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Order ID</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($user->orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-bold text-indigo-600">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $order->created_at->format('d M Y') }}</td>
                    <td class="px-4 py-3 font-bold">₹{{ number_format($order->total, 2) }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->status_badge_class }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-6 text-center text-gray-400">No orders yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
