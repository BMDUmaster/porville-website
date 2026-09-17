@php
    $cardUid = 'pc-' . $product->id . '-' . Str::random(6);
    $cardOptions = $product->card_variant_options;
    $cardFromPrice = $cardOptions[0]['price'] ?? $product->card_from_price;
    $cardMrp = $cardOptions[0]['mrp'] ?? collect($cardOptions)->max('mrp');
    $cardLowestPrice = $product->card_from_price;
    $cardInWishlist = in_array($product->id, session('wishlist', []), true);
    $cardIsNewArrival = in_array($product->id, $newArrivalProductIds ?? [], true);
@endphp
<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg">
    <button
        type="button"
        onclick="toggleWishlist({{ $product->id }}, this)"
        data-product-id="{{ $product->id }}"
        aria-label="{{ $cardInWishlist ? 'Remove from wishlist' : 'Add to wishlist' }}"
        title="{{ $cardInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}"
        class="wishlist-btn {{ $cardInWishlist ? 'active' : '' }} absolute right-2.5 top-2.5 z-20 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 shadow-md backdrop-blur-sm transition hover:scale-110"
    >
        <i class="{{ $cardInWishlist ? 'fa-solid fa-heart text-base text-red-500' : 'fa-regular fa-heart text-base text-slate-500 hover:text-red-500' }}"></i>
    </button>

    <a href="{{ route('frontend.product.show', $product->slug) }}" class="relative block aspect-[4/3] overflow-hidden bg-gray-50">
        @if($cardIsNewArrival)
            <span class="absolute left-2 top-2 z-10 rounded-full bg-black px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-amber-300 shadow">
                New Arrival
            </span>
        @endif
        @if($product->images && count($product->images))
            <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="flex h-full w-full items-center justify-center text-4xl text-slate-300">
                <i class="fa-solid fa-drumstick-bite"></i>
            </div>
        @endif
        @if($product->is_out_of_stock)
            <span class="absolute left-2 top-2 z-10 rounded bg-red-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-white shadow">
                Out of Stock
            </span>
        @endif
        @if($cardMrp && $cardMrp > $cardFromPrice)
            <span class="absolute bottom-2 right-2 z-10 rounded bg-red-500 px-2 py-0.5 text-[9px] font-bold text-white shadow">
                -{{ round((($cardMrp - $cardFromPrice) / $cardMrp) * 100) }}%
            </span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-2">
        <p class="text-[8px] font-bold uppercase tracking-[0.1em] text-slate-400">{{ $product->category->name ?? '' }}</p>
        <a href="{{ route('frontend.product.show', $product->slug) }}" class="font-classic mt-0.5 block min-h-[18px] text-[13px] font-bold leading-[1.15] text-slate-900 line-clamp-1 transition hover:text-amber-700">
            {{ $product->name }}
        </a>

        @if($product->is_enquiry_only)
            <div class="mt-1.5">
                <span class="inline-flex rounded-full bg-amber-50 px-2 py-1 text-[9px] font-bold text-amber-700">Custom cut &middot; enquire</span>
            </div>

            <div class="mt-auto pt-1.5">
                <p class="font-classic text-[14px] font-bold text-slate-900">On call</p>

                @if(!$product->is_out_of_stock && $product->contact_number)
                    <a href="tel:{{ preg_replace('/\D/', '', $product->contact_number) }}"
                       class="mt-1.5 flex h-8 w-full items-center justify-center gap-1.5 rounded-lg border border-amber-500 bg-black text-[9px] font-bold uppercase tracking-[0.08em] text-white transition hover:bg-neutral-900">
                        <i class="fa-solid fa-phone text-[10px] text-amber-400"></i> Call to Order
                    </a>
                @elseif($product->is_out_of_stock)
                    <span class="mt-1.5 flex h-8 w-full items-center justify-center rounded-lg border border-red-200 bg-red-50 text-[9px] font-bold uppercase tracking-wider text-red-600">
                        Out of Stock
                    </span>
                @else
                    <a href="{{ route('frontend.contact') }}"
                       class="mt-1.5 flex h-8 w-full items-center justify-center gap-1.5 rounded-lg border border-amber-500 bg-black text-[9px] font-bold uppercase tracking-[0.08em] text-white transition hover:bg-neutral-900">
                        <i class="fa-solid fa-headset text-[10px] text-amber-400"></i> Contact Us
                    </a>
                @endif
            </div>
        @else
            @if(count($cardOptions) > 1)
                <div class="mt-1.5">
                    <label for="{{ $cardUid }}" class="mb-0.5 block text-[8px] font-bold uppercase tracking-[0.1em] text-slate-400">Pack Size / Weight</label>
                    <select id="{{ $cardUid }}" data-card-price-target="{{ $cardUid }}-price" onchange="updateProductCardPrice('{{ $cardUid }}')" class="w-full rounded-md border border-slate-300 bg-white px-1.5 py-1 text-[11px] font-semibold text-slate-700 outline-none focus:border-amber-500">
                        @foreach($cardOptions as $opt)
                            <option value="{{ $opt['index'] ?? '' }}" data-price="{{ $opt['price'] }}" data-mrp="{{ $opt['mrp'] ?? '' }}">{{ $opt['label'] }}</option>
                        @endforeach
                    </select>
                </div>
            @else
                <input type="hidden" id="{{ $cardUid }}" value="{{ $cardOptions[0]['index'] ?? '' }}">
            @endif

            <div class="mt-auto pt-1.5">
                <div class="flex flex-wrap items-baseline gap-1.5">
                    <p id="{{ $cardUid }}-price" class="font-classic text-[14px] font-bold text-slate-900">
                        From Rs{{ number_format($cardFromPrice, 0) }}
                    </p>
                    <p id="{{ $cardUid }}-mrp" class="text-[10px] font-semibold text-slate-400 line-through {{ ($cardMrp && $cardMrp > $cardFromPrice) ? '' : 'hidden' }}">Rs{{ number_format($cardMrp, 0) }}</p>
                </div>

                @if(!$product->is_out_of_stock)
                    <button type="button"
                            onclick="addProductCardToCart({{ $product->id }}, '{{ $cardUid }}')"
                            class="mt-1.5 flex h-8 w-full items-center justify-center gap-1.5 rounded-lg border border-amber-500 bg-black text-[9px] font-bold uppercase tracking-[0.08em] text-white transition hover:bg-neutral-900">
                        <i class="fa-solid fa-cart-shopping text-[10px] text-amber-400"></i> Add to Cart
                    </button>
                @else
                    <span class="mt-1.5 flex h-8 w-full items-center justify-center rounded-lg border border-red-200 bg-red-50 text-[9px] font-bold uppercase tracking-wider text-red-600">
                        Out of Stock
                    </span>
                @endif
            </div>
        @endif
    </div>
</article>
