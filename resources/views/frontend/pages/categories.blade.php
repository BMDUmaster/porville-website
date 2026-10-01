@extends('frontend.layouts.app')
@section('title', 'All Categories — Porville')
@section('content')

@php($focus = request('focus'))

<section class="bg-black py-12 md:py-16">
    <div class="mx-auto max-w-6xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Browse</p>
        <h1 class="font-classic mt-3 text-4xl font-bold text-white md:text-5xl">All Categories</h1>
        @if($focus)
            <p class="mt-3 text-sm text-stone-300">
                Highlighted category: <span class="font-bold text-amber-400">{{ str_replace('-', ' ', $focus) }}</span>
            </p>
        @endif
    </div>
</section>

<section class="bg-[#faf7f0] py-12 md:py-16">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid grid-cols-2 gap-5 md:grid-cols-4">
            @foreach($categories as $cat)
                @php($isFocused = $focus === $cat->slug)
                <a
                    id="category-{{ $cat->slug }}"
                    href="{{ \App\Support\ShopUrl::to(['category' => $cat->slug]) }}"
                    class="group block overflow-hidden rounded-2xl border bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-amber-400 hover:shadow-lg {{ $isFocused ? 'ring-2 ring-amber-500 border-amber-300 shadow-lg' : 'border-gray-100' }}"
                >
                    <div class="aspect-square overflow-hidden bg-gray-50">
                        @if($cat->image)
                            <img src="{{ asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-5xl text-amber-200">
                                <i class="fa-solid fa-drumstick-bite"></i>
                            </div>
                        @endif
                    </div>
                    <div class="border-t border-gray-100 p-4">
                        <p class="font-classic text-base font-bold text-slate-900">{{ $cat->name }}</p>
                        <p class="mt-1 text-xs font-semibold text-amber-700">{{ $cat->products_count }} {{ Str::plural('product', $cat->products_count) }}</p>
                        @if($cat->children->count())
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($cat->children->take(3) as $sub)
                                    <span class="rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-semibold text-amber-700">{{ $sub->name }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

@endsection
