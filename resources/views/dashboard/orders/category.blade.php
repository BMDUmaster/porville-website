@extends('layouts.dashboard')
@section('title', 'Category Orders')
@section('page_title', 'Category Orders')

@section('content')
<div class="p-4 sm:p-6 max-w-7xl">

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
        @php
            $totalItems = $categories->count();
            $totalWeight = $categories->sum(fn($c) => $c->products->sum(fn($p) => $p->order_items_count));
        @endphp
        <div class="bg-white border rounded-xl p-4"><p class="text-xs text-gray-500">Total Items</p><p class="text-xl font-bold">{{ $totalItems }}</p></div>
        <div class="bg-white border rounded-xl p-4"><p class="text-xs text-gray-500">Total Weight</p><p class="text-xl font-bold text-purple-600">{{ $totalWeight }} kg</p></div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead style="background: linear-gradient(to right, #7e22ce, #4f46e5); color: white;">
                <tr>
                    <th class="px-4 py-3 text-left text-xs uppercase">Image</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">SR</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Products</th>
                    <th class="px-4 py-3 text-left text-xs uppercase">Demand</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $i => $cat)
                @php
                    $qty = $cat->products->sum(fn($p) => $p->order_items_count);
                    $demand = $qty > 300 ? ['High','text-red-600'] : ($qty > 150 ? ['Medium','text-yellow-600'] : ['Low','text-green-600']);
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        @if($cat->image)
                            <img src="{{ asset('storage/'.$cat->image) }}" class="w-12 h-12 rounded-lg object-cover">
                        @else
                            <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400"><i class="fa-regular fa-image"></i></div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">{{ $i + 1 }}</td>
                    <td class="px-4 py-3">
                        <p class="font-semibold text-sm">{{ $cat->name }}</p>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $cat->id }}</td>
                    <td class="px-4 py-3 text-blue-600 font-bold text-sm">{{ $cat->products_count }}</td>
                    <td class="px-4 py-3 font-semibold text-sm {{ $demand[1] }}">{{ $demand[0] }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
