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
                @php
                    $similarVariantIndex = collect($item->variants ?? [])->search(
                        fn ($variant) => filled($variant['selling_price'] ?? null) || filled($variant['today_price'] ?? null)
                    );
                    $similarVariantIndex = $similarVariantIndex === false ? null : $similarVariantIndex;
                    $similarVariant = $similarVariantIndex !== null ? ($item->variants[$similarVariantIndex] ?? null) : null;
                    $similarTomorrowPrice = $similarVariant && filled($similarVariant['tomorrow_price'] ?? null)
                        && (float) $similarVariant['tomorrow_price'] > 0
                            ? (float) $similarVariant['tomorrow_price']
                            : null;
                    $similarTodayPriceSet = $similarVariant && filled($similarVariant['today_price'] ?? null)
                        && (float) $similarVariant['today_price'] > 0;
                    // Today is only hidden when Tomorrow's price was explicitly set without a matching Today price.
                    $similarTodayAvailable = $similarTodayPriceSet || $similarTomorrowPrice === null;
                    $similarInWishlist = in_array($item->id, session('wishlist', []), true);
                @endphp
                <article class="group relative flex flex-col overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:border-green-200 hover:shadow-[0_18px_35px_rgba(15,23,42,0.1)]">
                    <button
                        type="button"
                        onclick="toggleWishlist({{ $item->id }}, this)"
                        data-product-id="{{ $item->id }}"
                        aria-label="{{ $similarInWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}"
                        title="{{ $similarInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
                        class="wishlist-btn {{ $similarInWishlist ? 'active' : '' }} absolute right-3 top-3 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-white/95 shadow-md backdrop-blur-sm transition hover:scale-110"
                    >
                        <i class="{{ $similarInWishlist ? 'fa-solid fa-heart text-red-500' : 'fa-regular fa-heart text-slate-600' }}"></i>
                    </button>

                    <a href="{{ route('frontend.product.show', $item->slug) }}" class="relative block aspect-[1/0.9] overflow-hidden bg-[#f4f5ef]">
                        @if(in_array($item->id, $newArrivalProductIds ?? [], true))
                            <span class="absolute left-2 top-2 z-10 rounded-full bg-blue-600 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.14em] text-white shadow-sm">
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
                    </a>
                    <div class="flex flex-1 flex-col p-4">
                        <div class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">{{ $item->category->name ?? 'Fresh cut' }}</div>
                        <a href="{{ route('frontend.product.show', $item->slug) }}" class="mt-2 line-clamp-2 min-h-[40px] text-[13px] font-black leading-5 text-slate-900 transition hover:text-green-700">{{ $item->name }}</a>

                        <div class="mt-auto pt-3">
                            @if($similarTodayAvailable)
                                <div class="flex items-end justify-between gap-2 border-t border-dashed border-slate-100 pt-3">
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-wider text-emerald-600">Today</p>
                                        <p class="text-[16px] font-black text-slate-900">Rs{{ number_format($item->display_price, 0) }}<span class="ml-1 text-[11px] font-semibold text-slate-400">{{ $item->display_pack_label }}</span></p>
                                        @if($item->display_mrp && $item->display_mrp > $item->display_price)
                                            <p class="text-[11px] font-bold text-slate-400 line-through">Rs{{ number_format($item->display_mrp, 0) }}</p>
                                        @endif
                                    </div>
                                    @if(!$item->is_out_of_stock)
                                        <button type="button" onclick="addToCart({{ $item->id }}, {{ $similarVariantIndex === null ? 'null' : $similarVariantIndex }}, 'today')" aria-label="Add {{ $item->name }} for today" title="Add for today" class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-green-700 text-white transition hover:bg-green-800">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                        </button>
                                    @endif
                                </div>
                            @endif

                            @if(!$item->is_out_of_stock && $similarTomorrowPrice !== null)
                                <div class="{{ $similarTodayAvailable ? 'mt-2 border-t border-dashed border-slate-100 pt-2' : 'border-t border-dashed border-slate-100 pt-3' }} flex items-end justify-between gap-2">
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-wider text-amber-700">Tomorrow</p>
                                        <p class="text-[16px] font-black text-slate-900">Rs{{ number_format($similarTomorrowPrice, 0) }}<span class="ml-1 text-[11px] font-semibold text-slate-400">{{ $item->display_pack_label }}</span></p>
                                    </div>
                                    <button type="button" onclick="addToCart({{ $item->id }}, {{ $similarVariantIndex }}, 'tomorrow')" aria-label="Add {{ $item->name }} for tomorrow" title="Add for tomorrow" class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-amber-500 text-white transition hover:bg-amber-600">
                                        <i class="fa-solid fa-cart-shopping"></i>
                                    </button>
                                </div>
                            @elseif($item->is_out_of_stock)
                                <span class="mt-3 inline-flex w-fit rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-[10px] font-bold uppercase tracking-wider text-red-600">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
