{{-- Admin-written SEO content for the current category / sub category page, shown just above the footer. --}}
@php
    $seoSubcategory = request()->filled('subcategory')
        ? \App\Models\Category::subcategories()->where('slug', request('subcategory'))->first()
        : null;
    $categorySeo = \App\Models\CategorySeoContent::forPage($activeCategory ?? null, $seoSubcategory);
@endphp
@if($categorySeo)
    @include('partials.seo-rich-styles')
    <section class="border-t border-slate-200 bg-white py-10 md:py-14">
        <div class="mx-auto max-w-5xl px-4">
            @if($categorySeo->heading)
                <h2 class="font-classic mb-4 text-2xl font-black text-slate-900 md:text-3xl">{{ $categorySeo->heading }}</h2>
            @endif
            <div class="seo-rich">{!! $categorySeo->content !!}</div>
        </div>
    </section>
@endif
