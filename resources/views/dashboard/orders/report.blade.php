@extends('layouts.dashboard')
@section('title', 'Orders Report')
@section('page_title', 'Orders Report')

@section('content')
<div class="max-w-7xl">

    {{-- Header Banner --}}
    <div class="bg-gradient-to-r from-mayview-blue via-mayview-accent to-blue-500 text-white px-6 py-8">
        <p class="opacity-90 mt-2">Category & Sub-category Sales Overview</p>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Date Filter --}}
        <form method="GET" class="bg-white p-4 rounded-xl shadow flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2 w-full md:w-auto">
                <label class="text-sm font-medium text-gray-600">Select Date:</label>
                <input type="date" name="date" value="{{ $date }}"
                       class="border rounded-lg px-3 py-2 text-sm w-full md:w-auto outline-none focus:border-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">Apply</button>
        </form>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="bg-gradient-to-r from-green-500 to-emerald-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm opacity-80">Total Sales</p>
                <h3 class="text-3xl font-bold mt-2">₹{{ number_format($totalSales, 2) }}</h3>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm opacity-80">Total Quantity</p>
                <h3 class="text-3xl font-bold mt-2">{{ $totalQty }} Kg</h3>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 rounded-xl shadow">
                <p class="text-sm opacity-80">Categories</p>
                <h3 class="text-3xl font-bold mt-2">{{ count($byCategory) }}</h3>
            </div>
        </div>

        {{-- Category Cards --}}
        @if(count($byCategory))
        <div class="bg-white p-6 rounded-xl shadow">
            <h2 class="text-lg font-semibold mb-4">Category-wise Sales</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($byCategory as $cat => $data)
                <div class="p-4 rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 hover:shadow-lg hover:scale-[1.02] transition cursor-pointer">
                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-gray-700">{{ $cat }}</h3>
                        <span class="text-sm text-gray-500">₹{{ number_format($data['amount'], 2) }}</span>
                    </div>
                    <p class="text-xl font-bold text-indigo-600 mt-2">{{ $data['qty'] }} Kg</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Sub-category Table --}}
        <div class="bg-white rounded-xl shadow">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">Sub-category Sales</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left">Category</th>
                            <th class="px-4 py-3 text-left">Sub Category</th>
                            <th class="px-4 py-3 text-left">Quantity</th>
                            <th class="px-4 py-3 text-left">Sales</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($byCategory as $cat => $data)
                            @foreach($data['subs'] as $sub => $subData)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">{{ $cat }}</td>
                                <td class="px-4 py-3">{{ $sub }}</td>
                                <td class="px-4 py-3">{{ $subData['qty'] }} Kg</td>
                                <td class="px-4 py-3">₹{{ number_format($subData['amount'], 2) }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white rounded-xl p-8 text-center text-gray-400">
            No sales data for {{ \Carbon\Carbon::parse($date)->format('d M Y') }}.
        </div>
        @endif
    </div>
</div>
@endsection
