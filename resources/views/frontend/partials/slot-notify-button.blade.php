{{-- Shown instead of Add to Cart while a product's ordering slot is closed. --}}
@php
    $isCard = ($size ?? 'card') === 'card';
    $classes = $isCard
        ? 'mt-1.5 flex h-8 w-full items-center justify-center gap-1.5 rounded-lg text-[9px] font-bold uppercase tracking-[0.08em]'
        : 'col-span-3 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-[13px] font-black uppercase tracking-[0.14em] sm:flex-1';
@endphp
@if($alerted)
    <span class="{{ $classes }} border border-emerald-200 bg-emerald-50 text-emerald-700">
        <i class="fa-solid fa-bell"></i> We'll notify you
    </span>
@elseif(auth('web_frontend')->check())
    <button type="button" onclick="notifyProductSlot({{ $productId }}, this)"
            class="{{ $classes }} border border-amber-500 bg-amber-50 text-amber-800 transition hover:bg-amber-100">
        <i class="fa-regular fa-bell"></i> Notify Me
    </button>
@else
    {{-- Guests log in first, then the alert is saved automatically. --}}
    <a href="{{ route('frontend.slot-alerts.subscribe', $productId) }}"
       class="{{ $classes }} border border-amber-500 bg-amber-50 text-amber-800 transition hover:bg-amber-100">
        <i class="fa-regular fa-bell"></i> Notify Me
    </a>
@endif
