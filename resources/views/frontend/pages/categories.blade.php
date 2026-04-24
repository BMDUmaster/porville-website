@extends('frontend.layouts.app')
@section('title', 'All Categories')
@section('content')

@php($focus = request('focus'))

<div class="mx-auto max-w-6xl px-4 py-10">
    <div class="mb-6 flex flex-col gap-2">
        <h1 class="nunito text-2xl font-extrabold text-gray-800">All Categories</h1>
        @if($focus)
            <p class="text-sm text-gray-500">
                Highlighted category: <span class="font-bold text-blue-600">{{ str_replace('-', ' ', $focus) }}</span>
            </p>
        @endif
    </div>

    <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
        @foreach($categories as $cat)
            @php($isFocused = $focus === $cat->slug)
            <a
                id="category-{{ $cat->slug }}"
                href="{{ route('frontend.products', ['category' => $cat->slug]) }}"
                class="block overflow-hidden rounded-2xl border bg-white transition duration-300 hover:-translate-y-1 hover:border-green-400 hover:shadow-lg {{ $isFocused ? 'ring-2 ring-blue-500 border-blue-300 shadow-lg' : 'border-gray-200' }}"
            >
                <div class="aspect-square overflow-hidden bg-gray-50">
                    @if($cat->image)
                        <img src="{{ asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center text-5xl text-gray-300">C</div>
                    @endif
                </div>
                <div class="border-t p-4">
                    <p class="font-bold text-gray-800">{{ $cat->name }}</p>
                    <p class="mt-1 text-xs text-gray-400">{{ $cat->products_count }} products</p>
                    @if($cat->children->count())
                        <div class="mt-2 flex flex-wrap gap-1">
                            @foreach($cat->children->take(3) as $sub)
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] text-gray-600">{{ $sub->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </a>
        @endforeach
    </div>
</div>

@endsection
