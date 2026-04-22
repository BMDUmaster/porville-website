@extends('frontend.layouts.app')
@section('title', 'All Categories')
@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">All Categories</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        @foreach($categories as $cat)
        <a href="{{ route('frontend.products', ['category' => $cat->slug]) }}"
           class="bg-white rounded-2xl border hover:border-green-400 hover:shadow-lg transition overflow-hidden block">
            <div class="aspect-square bg-gray-50 overflow-hidden">
                @if($cat->image)
                    <img src="{{ asset('storage/'.$cat->image) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-5xl">🥩</div>
                @endif
            </div>
            <div class="p-4 border-t">
                <p class="font-bold text-gray-800">{{ $cat->name }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $cat->products_count }} products</p>
                @if($cat->children->count())
                    <div class="mt-2 flex flex-wrap gap-1">
                        @foreach($cat->children->take(3) as $sub)
                            <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">{{ $sub->name }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </a>
        @endforeach
    </div>
</div>
@endsection
