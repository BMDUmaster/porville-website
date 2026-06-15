@if($similar->count())
    <div class="{{ $wrapperClass ?? 'mt-10' }}">
        <div class="mb-5 flex items-center justify-between gap-3">
            <h2 class="text-[24px] font-black text-slate-900">
                Similar <span class="text-[#2f8c43]">Products</span>
            </h2>
            <a href="{{ route('frontend.products', ['category' => $product->category->slug ?? null]) }}" class="text-[12px] font-black uppercase tracking-[0.18em] text-[#2d72d3] transition hover:text-[#1f5bb4]">
                View All
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach($similar as $item)
                <a href="{{ route('frontend.product.show', $item->slug) }}"
                   class="group overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-green-200 hover:shadow-[0_18px_35px_rgba(15,23,42,0.1)]">
                    <div class="relative aspect-[1/0.9] overflow-hidden bg-[#f4f5ef]">
                        @if(in_array($item->id, $newArrivalProductIds ?? [], true))
                            <span class="absolute left-3 top-3 z-10 rounded-full bg-blue-600 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.14em] text-white shadow-sm">
                                New Arrival
                            </span>
                        @endif
                        @if($item->images && count($item->images))
                            <img src="{{ asset('storage/' . $item->images[0]) }}" alt="{{ $item->name }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        @else
                            <div class="flex h-full w-full items-center justify-center text-4xl text-slate-300">
                                <i class="fa-solid fa-drumstick-bite"></i>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ $item->category->name ?? 'Fresh cut' }}</div>
                        <div class="mt-2 line-clamp-2 text-[13px] font-black leading-5 text-slate-900">{{ $item->name }}</div>
                        <div class="mt-3 flex flex-wrap items-center gap-2">
                            <span class="text-[16px] font-black text-slate-900">Rs{{ number_format($item->display_price, 0) }}</span>
                            <span class="text-[11px] font-semibold text-slate-400">{{ $item->display_pack_label }}</span>
                            @if($item->display_mrp && $item->display_mrp > $item->display_price)
                                <span class="text-[12px] font-bold text-slate-400 line-through">Rs{{ number_format($item->display_mrp, 0) }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
