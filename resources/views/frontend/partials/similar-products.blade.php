@if($similar->count())
    <div class="{{ $wrapperClass ?? 'mt-10' }}">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h2 class="text-[24px] font-black text-slate-900">
                Similar <span class="text-[#b8862c]">Products</span>
            </h2>
            <a href="{{ route('frontend.products', ['category' => $product->category->slug ?? null]) }}" class="text-[12px] font-black uppercase tracking-[0.18em] text-[#b8862c] transition hover:text-[#8f6a1c]">
                View All
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($similar as $item)
                @include('frontend.partials.product-card', ['product' => $item])
            @endforeach
        </div>
    </div>
@endif
