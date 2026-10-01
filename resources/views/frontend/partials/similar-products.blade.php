@if($similar->count())
    <div class="{{ $wrapperClass ?? 'mt-10' }}">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h2 class="text-[24px] font-black text-slate-900">
                Similar <span class="text-[#b8862c]">Products</span>
            </h2>
            <a href="{{ route('frontend.products', ['category' => $product->category->slug ?? null]) }}" class="shrink-0 whitespace-nowrap text-[12px] font-black uppercase tracking-[0.18em] text-[#b8862c] transition hover:text-[#8f6a1c]">
                View All
            </a>
        </div>

        {{-- Mobile: one swipeable row showing 1½ cards. Desktop: grid. --}}
        <div class="scrollbar-hide flex snap-x snap-mandatory gap-3 overflow-x-auto pb-2 md:grid md:snap-none md:grid-cols-2 md:gap-4 md:overflow-visible md:pb-0 lg:grid-cols-4">
            @foreach($similar as $item)
                <div class="w-[62%] shrink-0 snap-start md:w-auto">
                    @include('frontend.partials.product-card', ['product' => $item])
                </div>
            @endforeach
        </div>
    </div>
@endif
