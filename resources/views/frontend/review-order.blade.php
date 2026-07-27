@extends('frontend.layouts.app')
@section('title', 'Review Your Order')

@section('content')
<section class="bg-slate-50 px-4 py-10 md:py-16">
    <div class="mx-auto max-w-2xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">
        <div class="bg-gradient-to-r from-green-700 to-emerald-500 px-6 py-8 text-center text-white md:px-10">
            <i class="fa-solid fa-star text-3xl text-yellow-300"></i>
            <h1 class="mt-3 text-2xl font-black md:text-3xl">How was your FarmSea order?</h1>
            <p class="mt-2 text-sm text-white/80">Order {{ $order->order_number ?: '#'.$order->id }}</p>
        </div>

        <div class="p-5 md:p-8">
            @if(session('success'))
                <div class="mb-5 rounded-2xl bg-green-50 p-4 text-sm font-bold text-green-700">{{ session('success') }}</div>
            @endif

            <div class="mb-6 rounded-2xl bg-slate-50 p-4">
                <p class="text-xs font-black uppercase tracking-wider text-slate-400">Your delivered items</p>
                <p class="mt-2 text-sm font-semibold text-slate-700">{{ $order->items->pluck('product.name')->filter()->join(', ') }}</p>
            </div>

            @if($order->review)
                <div class="py-8 text-center">
                    <div class="text-3xl text-yellow-400">{{ str_repeat('★', $order->review->rating) }}<span class="text-slate-200">{{ str_repeat('★', 5 - $order->review->rating) }}</span></div>
                    <h2 class="mt-4 text-xl font-black text-slate-900">Review already submitted</h2>
                    <p class="mt-2 text-sm text-slate-500">Thank you for sharing your feedback with us.</p>
                </div>
            @else
                <form method="POST" action="{{ request()->fullUrl() }}">
                    @csrf
                    <div class="mb-6">
                        <label for="product_id" class="mb-2 block text-sm font-bold text-slate-700">Which product are you reviewing?</label>
                        <select id="product_id" name="product_id" required class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold outline-none focus:border-green-500">
                            @foreach($order->items->unique('product_id') as $item)
                                <option value="{{ $item->product_id }}" {{ old('product_id') == $item->product_id ? 'selected' : '' }}>{{ $item->product?->name ?? 'Product' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <fieldset>
                        <legend class="text-center text-sm font-bold text-slate-700">Tap a star to rate your order</legend>
                        <div class="mt-3 flex flex-row-reverse justify-center gap-2" id="ratingStars">
                            @for($rating = 5; $rating >= 1; $rating--)
                                <input class="peer hidden" type="radio" name="rating" value="{{ $rating }}" id="rating{{ $rating }}" {{ old('rating') == $rating ? 'checked' : '' }} required>
                                <label for="rating{{ $rating }}" class="cursor-pointer text-4xl text-slate-200 transition hover:text-yellow-400 peer-checked:text-yellow-400 peer-hover:text-yellow-400">★</label>
                            @endfor
                        </div>
                        @error('rating')<p class="mt-2 text-center text-xs text-red-600">{{ $message }}</p>@enderror
                    </fieldset>

                    <div class="mt-6">
                        <label for="comment" class="mb-2 block text-sm font-bold text-slate-700">Tell us more (optional)</label>
                        <textarea id="comment" name="comment" rows="5" maxlength="1500" placeholder="Freshness, packaging, delivery experience..." class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm outline-none focus:border-green-500">{{ old('comment') }}</textarea>
                        @error('comment')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="mt-5 w-full rounded-2xl bg-green-700 px-5 py-3.5 text-sm font-black text-white hover:bg-green-800">Submit Review</button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
