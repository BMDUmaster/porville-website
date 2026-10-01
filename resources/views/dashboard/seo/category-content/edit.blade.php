@extends('layouts.dashboard')
@section('title', 'Category Page Content')
@section('page_title', 'SEO Management')

@section('content')
@php
    $pageUrl = $category->parent_id
        ? route('frontend.products', ['category' => $category->parent?->slug, 'subcategory' => $category->slug])
        : route('frontend.products', ['category' => $category->slug]);
    $tags = [
        ['H2', '<h2>', '</h2>'],
        ['H3', '<h3>', '</h3>'],
        ['P', '<p>', '</p>'],
        ['Bold', '<strong>', '</strong>'],
        ['Italic', '<em>', '</em>'],
        ['Link', '<a href="https://">', '</a>'],
        ['List', "<ul>\n  <li>", "</li>\n  <li></li>\n</ul>"],
        ['Numbered', "<ol>\n  <li>", "</li>\n  <li></li>\n</ol>"],
        ['Quote', '<blockquote>', '</blockquote>'],
        ['Line', "<hr>\n", ''],
        ['Break', '<br>', ''],
    ];
@endphp
@include('partials.seo-rich-styles')
<div class="mx-auto max-w-6xl space-y-5 p-4 md:p-6">

    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <a href="{{ route('dashboard.seo.category-content') }}" class="text-xs font-bold text-slate-500 hover:text-amber-700"><i class="fa-solid fa-arrow-left mr-1"></i> Category Page Content</a>
            <h2 class="mt-1 text-2xl font-black text-slate-900">{{ $category->parent ? $category->parent->name . ' › ' : '' }}{{ $category->name }}</h2>
            <p class="mt-1 text-sm text-slate-500">Shown above the footer on <a href="{{ $pageUrl }}" target="_blank" rel="noopener" class="font-semibold text-amber-700 underline">this category page</a>.</p>
        </div>
        @if($content->exists)
            <form method="POST" action="{{ route('dashboard.seo.category-content.destroy', $category) }}" onsubmit="return confirm('Remove the page content for {{ addslashes($category->name) }}?')">
                @csrf @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50">
                    <i class="fa-regular fa-trash-can"></i> Delete Content
                </button>
            </form>
        @endif
    </div>

    <form method="POST" action="{{ route('dashboard.seo.category-content.update', $category) }}" class="grid gap-5 lg:grid-cols-2">
        @csrf @method('PUT')

        {{-- Editor --}}
        <div class="space-y-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Heading <span class="font-normal text-slate-400">(optional, shown as the section title)</span></label>
                <input type="text" name="heading" id="seoHeading" maxlength="200" value="{{ old('heading', $content->heading) }}" placeholder="e.g. Buy Fresh Chicken Online in Delhi"
                       class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-amber-500">
            </div>

            <div>
                <div class="mb-1.5 flex items-center justify-between">
                    <label class="text-xs font-bold text-slate-600">Content (HTML)</label>
                    <span class="text-[11px] text-slate-400">Select text, then click a tag</span>
                </div>
                <div class="flex flex-wrap gap-1.5 rounded-t-xl border border-b-0 border-slate-300 bg-slate-50 p-2">
                    @foreach($tags as [$label, $open, $close])
                        <button type="button" data-open="{{ $open }}" data-close="{{ $close }}" onclick="insertSeoTag(this)"
                                class="rounded-md border border-slate-200 bg-white px-2.5 py-1 text-xs font-bold text-slate-700 hover:border-amber-400 hover:text-amber-700">{{ $label }}</button>
                    @endforeach
                </div>
                <textarea name="content" id="seoContent" rows="18" spellcheck="false"
                          placeholder="<h2>Fresh Chicken Delivered in Delhi</h2>&#10;<p>Write about this category…</p>"
                          class="w-full rounded-b-xl border border-slate-300 px-3 py-3 font-mono text-[13px] leading-6 outline-none focus:border-amber-500">{{ old('content', $content->content) }}</textarea>
                <p class="mt-1 text-[11px] text-slate-400">Allowed: h2, h3, h4, p, br, strong, b, em, i, u, a, ul, ol, li, blockquote, hr, span, div, table, img. Scripts are removed automatically.</p>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" class="h-4 w-4 accent-amber-600" {{ old('is_active', $content->exists ? (int) $content->is_active : 1) ? 'checked' : '' }}>
                    Show on website
                </label>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-black px-6 py-3 text-sm font-bold text-white hover:bg-neutral-800">
                    <i class="fa-solid fa-floppy-disk"></i> Save Content
                </button>
            </div>
        </div>

        {{-- Live preview --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-400">Live Preview</p>
            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-5">
                <h2 id="seoPreviewHeading" class="font-classic mb-3 text-2xl font-black text-slate-900"></h2>
                <div id="seoPreview" class="seo-rich"></div>
                <p id="seoPreviewEmpty" class="text-sm text-slate-400">Start typing to see how it looks on the website.</p>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
const seoContent = document.getElementById('seoContent');
const seoHeading = document.getElementById('seoHeading');

function insertSeoTag(button) {
    const open = button.dataset.open;
    const close = button.dataset.close;
    const start = seoContent.selectionStart;
    const end = seoContent.selectionEnd;
    const selected = seoContent.value.slice(start, end);

    seoContent.setRangeText(open + selected + close, start, end, 'end');
    // Leave the cursor inside the new tag when nothing was selected.
    if (!selected && close) {
        const cursor = start + open.length;
        seoContent.setSelectionRange(cursor, cursor);
    }
    seoContent.focus();
    renderSeoPreview();
}

function renderSeoPreview() {
    // Preview only: drop scripts and inline handlers (the server sanitises again on save).
    const template = document.createElement('template');
    template.innerHTML = seoContent.value;
    template.content.querySelectorAll('script,style,iframe,object,embed').forEach((el) => el.remove());
    template.content.querySelectorAll('*').forEach((el) => {
        [...el.attributes].forEach((attr) => {
            if (/^on/i.test(attr.name) || /^\s*javascript:/i.test(attr.value)) el.removeAttribute(attr.name);
        });
    });

    const preview = document.getElementById('seoPreview');
    preview.replaceChildren(template.content);
    document.getElementById('seoPreviewHeading').textContent = seoHeading.value;
    document.getElementById('seoPreviewHeading').classList.toggle('hidden', !seoHeading.value.trim());
    document.getElementById('seoPreviewEmpty').classList.toggle('hidden', seoContent.value.trim() !== '' || seoHeading.value.trim() !== '');
}

seoContent.addEventListener('input', renderSeoPreview);
seoHeading.addEventListener('input', renderSeoPreview);
renderSeoPreview();
</script>
@endsection
