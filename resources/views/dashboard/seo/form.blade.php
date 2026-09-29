@extends('layouts.dashboard')
@php
    $isEdit = $seoPage->exists;
    $v = fn (string $field) => old($field, $seoPage->{$field});
    $on = fn (string $field) => (bool) old($field, $seoPage->{$field});
    $input = 'w-full rounded-xl border px-4 py-2.5 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:ring-2 focus:ring-amber-100';
    $border = fn (string $field) => $errors->has($field) ? 'border-red-400' : 'border-slate-200';
    $label = 'mb-1.5 block text-xs font-bold uppercase tracking-wide text-slate-600';
    $externalUrl = fn (?string $value) => $value && Str::startsWith($value, ['http://', 'https://', '//']) ? $value : null;
@endphp
@section('title', $isEdit ? 'Edit Page — SEO' : 'Add New Page — SEO')
@section('page_title', $isEdit ? 'Edit Page — SEO' : 'Add New Page — SEO')

@section('content')
<div class="p-4 md:p-6 mx-auto max-w-7xl">

    <a href="{{ route('dashboard.seo') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-amber-700">
        <i class="fa-solid fa-arrow-left"></i> Back to SEO Management
    </a>
    <h2 class="mt-2 text-2xl font-black text-slate-900">{{ $isEdit ? 'Edit Page' : 'Add New Page' }}</h2>
    <p class="mt-1 text-sm text-slate-500">Give this page a name and its live route path, then fill in the SEO tabs below.</p>

    <form id="seoForm" method="POST" enctype="multipart/form-data"
          action="{{ $isEdit ? route('dashboard.seo.update', $seoPage) : route('dashboard.seo.store') }}"
          class="mt-5 space-y-5">
        @csrf
        @if($isEdit) @method('PUT') @endif

        {{-- Page identity --}}
        <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:grid-cols-3">
            <div>
                <label class="{{ $label }}" for="page_name">Page Name <span class="text-red-500">*</span></label>
                <input type="text" id="page_name" name="page_name" required maxlength="150" value="{{ $v('page_name') }}"
                       placeholder="e.g. About Us" class="{{ $input }} {{ $border('page_name') }}">
                @error('page_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="{{ $label }}" for="route_path">Route Path <span class="text-red-500">*</span></label>
                <input type="text" id="route_path" name="route_path" required maxlength="255" list="availablePages" value="{{ $v('route_path') }}"
                       placeholder="/about-us" class="{{ $input }} {{ $border('route_path') }}">
                <datalist id="availablePages">
                    @foreach($availablePages as $path => $name)
                        <option value="{{ $path }}">{{ $name }}</option>
                    @endforeach
                </datalist>
                @error('route_path')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @else
                    <p class="mt-1 text-[11px] text-slate-400">Start typing to pick a storefront page, e.g. <code>/</code> for Home.</p>
                @enderror
            </div>
            <div>
                <label class="{{ $label }}" for="is_active">Status</label>
                <select id="is_active" name="is_active" class="{{ $input }} {{ $border('is_active') }} bg-white">
                    <option value="1" @selected($on('is_active'))>Active</option>
                    <option value="0" @selected(! $on('is_active'))>Inactive</option>
                </select>
                <p class="mt-1 text-[11px] text-slate-400">Inactive pages fall back to the site's default tags.</p>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-3">
            {{-- Tabs --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
                <div class="-mx-1 flex gap-1 overflow-x-auto border-b border-slate-200 scrollbar-hide" role="tablist">
                    @foreach(['basic' => 'Basic SEO', 'og' => 'Open Graph', 'twitter' => 'Twitter Card', 'schema' => 'Schema (JSON-LD)', 'crawl' => 'Sitemap / Crawl'] as $key => $tab)
                        <button type="button" role="tab" data-tab="{{ $key }}"
                                class="seo-tab whitespace-nowrap border-b-2 border-transparent px-3 py-2.5 text-xs font-bold text-slate-500 transition hover:text-slate-800">
                            {{ $tab }}
                            <span class="tab-error-dot ml-1 hidden h-1.5 w-1.5 rounded-full bg-red-500 align-middle"></span>
                        </button>
                    @endforeach
                </div>

                {{-- Basic SEO --}}
                <div class="seo-pane space-y-4 pt-5" data-pane="basic">
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="{{ $label }}" for="meta_title">Meta Title</label>
                            <span class="text-[11px] font-semibold text-slate-400" data-counter="meta_title" data-max="60">0 / 60</span>
                        </div>
                        <input type="text" id="meta_title" name="meta_title" maxlength="255" value="{{ $v('meta_title') }}"
                               placeholder="Fresh Cut Chicken & Mutton Delivered Daily | Porville" class="{{ $input }} {{ $border('meta_title') }}">
                        @error('meta_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">Optimal length is 50–60 characters.</p>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="{{ $label }}" for="meta_description">Meta Description</label>
                            <span class="text-[11px] font-semibold text-slate-400" data-counter="meta_description" data-max="160">0 / 160</span>
                        </div>
                        <textarea id="meta_description" name="meta_description" rows="3" maxlength="1000"
                                  placeholder="Short summary shown under the title in Google results."
                                  class="{{ $input }} {{ $border('meta_description') }}">{{ $v('meta_description') }}</textarea>
                        @error('meta_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <p class="mt-1 text-[11px] text-slate-400">Optimal length is 120–160 characters.</p>
                    </div>
                    <div>
                        <label class="{{ $label }}" for="meta_keywords">Meta Keywords <span class="font-medium normal-case text-slate-400">(comma separated)</span></label>
                        <input type="text" id="meta_keywords" name="meta_keywords" maxlength="1000" value="{{ $v('meta_keywords') }}"
                               placeholder="fresh chicken, mutton delivery, fresh fish online" class="{{ $input }} {{ $border('meta_keywords') }}">
                        @error('meta_keywords') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        <div id="keywordChips" class="mt-2 flex flex-wrap gap-1.5"></div>
                    </div>
                    <div>
                        <label class="{{ $label }}" for="canonical_url">Canonical URL <span class="font-medium normal-case text-slate-400">(auto-generated if blank)</span></label>
                        <input type="url" id="canonical_url" name="canonical_url" maxlength="500" value="{{ $v('canonical_url') }}"
                               placeholder="{{ url('/') }}" class="{{ $input }} {{ $border('canonical_url') }}">
                        @error('canonical_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Open Graph --}}
                <div class="seo-pane hidden space-y-4 pt-5" data-pane="og">
                    <p class="rounded-xl bg-blue-50 px-4 py-2.5 text-xs text-blue-700"><i class="fa-solid fa-circle-info mr-1"></i>Used by Facebook, WhatsApp, LinkedIn when the link is shared. Blank fields fall back to the meta title/description.</p>
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="{{ $label }}" for="og_title">OG Title</label>
                            <span class="text-[11px] font-semibold text-slate-400" data-counter="og_title" data-max="60">0 / 60</span>
                        </div>
                        <input type="text" id="og_title" name="og_title" maxlength="255" value="{{ $v('og_title') }}" class="{{ $input }} {{ $border('og_title') }}">
                        @error('og_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="{{ $label }}" for="og_description">OG Description</label>
                            <span class="text-[11px] font-semibold text-slate-400" data-counter="og_description" data-max="200">0 / 200</span>
                        </div>
                        <textarea id="og_description" name="og_description" rows="3" maxlength="1000" class="{{ $input }} {{ $border('og_description') }}">{{ $v('og_description') }}</textarea>
                        @error('og_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $label }}" for="og_type">OG Type</label>
                        <select id="og_type" name="og_type" class="{{ $input }} {{ $border('og_type') }} bg-white">
                            @foreach(\App\Models\SeoPage::OG_TYPES as $type)
                                <option value="{{ $type }}" @selected($v('og_type') === $type)>{{ ucfirst($type) }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('dashboard.seo.partials.image-field', [
                        'prefix' => 'og', 'title' => 'OG Image', 'hint' => 'Recommended 1200 × 630 px, JPG/PNG/WebP, max 4 MB.',
                        'current' => $seoPage->og_image_url, 'externalUrl' => $externalUrl($seoPage->og_image),
                    ])
                </div>

                {{-- Twitter --}}
                <div class="seo-pane hidden space-y-4 pt-5" data-pane="twitter">
                    <p class="rounded-xl bg-blue-50 px-4 py-2.5 text-xs text-blue-700"><i class="fa-solid fa-circle-info mr-1"></i>Blank fields fall back to the Open Graph values.</p>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $label }}" for="twitter_card">Card Type</label>
                            <select id="twitter_card" name="twitter_card" class="{{ $input }} {{ $border('twitter_card') }} bg-white">
                                <option value="summary_large_image" @selected($v('twitter_card') === 'summary_large_image')>Summary with large image</option>
                                <option value="summary" @selected($v('twitter_card') === 'summary')>Summary</option>
                            </select>
                        </div>
                        <div>
                            <label class="{{ $label }}" for="twitter_site">Twitter / X Handle</label>
                            <input type="text" id="twitter_site" name="twitter_site" maxlength="100" value="{{ $v('twitter_site') }}" placeholder="@porville" class="{{ $input }} {{ $border('twitter_site') }}">
                            @error('twitter_site') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="{{ $label }}" for="twitter_title">Twitter Title</label>
                        <input type="text" id="twitter_title" name="twitter_title" maxlength="255" value="{{ $v('twitter_title') }}" class="{{ $input }} {{ $border('twitter_title') }}">
                        @error('twitter_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $label }}" for="twitter_description">Twitter Description</label>
                        <textarea id="twitter_description" name="twitter_description" rows="3" maxlength="1000" class="{{ $input }} {{ $border('twitter_description') }}">{{ $v('twitter_description') }}</textarea>
                        @error('twitter_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    @include('dashboard.seo.partials.image-field', [
                        'prefix' => 'twitter', 'title' => 'Twitter Image', 'hint' => 'Leave empty to reuse the OG image.',
                        'current' => $seoPage->twitter_image_url, 'externalUrl' => $externalUrl($seoPage->twitter_image),
                    ])
                </div>

                {{-- Schema --}}
                <div class="seo-pane hidden space-y-3 pt-5" data-pane="schema">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-bold text-slate-500">Insert template:</span>
                        @foreach(['WebPage', 'Organization', 'LocalBusiness', 'BreadcrumbList', 'FAQPage'] as $template)
                            <button type="button" data-schema-template="{{ $template }}"
                                    class="rounded-lg border border-slate-200 px-2.5 py-1 text-[11px] font-bold text-slate-600 hover:border-amber-400 hover:text-amber-700">{{ $template }}</button>
                        @endforeach
                    </div>
                    <textarea id="schema_json" name="schema_json" rows="16" spellcheck="false"
                              placeholder='{ "@context": "https://schema.org", "@type": "WebPage", ... }'
                              class="{{ $input }} {{ $border('schema_json') }} font-mono text-xs leading-5">{{ $v('schema_json') }}</textarea>
                    @error('schema_json') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    <div class="flex items-center justify-between gap-3">
                        <p id="schemaStatus" class="text-xs font-semibold text-slate-400"></p>
                        <div class="flex gap-2">
                            <button type="button" id="schemaFormat" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-200"><i class="fa-solid fa-wand-magic-sparkles mr-1"></i>Format</button>
                            <a href="https://validator.schema.org/" target="_blank" rel="noopener" class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-200"><i class="fa-solid fa-arrow-up-right-from-square mr-1"></i>Validator</a>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400">Paste one JSON-LD object, or an array of objects. It is output inside <code>&lt;script type="application/ld+json"&gt;</code> on this page.</p>
                </div>

                {{-- Crawl --}}
                <div class="seo-pane hidden space-y-4 pt-5" data-pane="crawl">
                    @foreach([
                        'robots_index' => ['Allow search engines to index this page', 'Off adds noindex — the page disappears from Google results.'],
                        'robots_follow' => ['Allow search engines to follow links', 'Off adds nofollow to the robots meta tag.'],
                        'sitemap_include' => ['Include in sitemap.xml', 'Noindexed pages are always left out of the sitemap.'],
                    ] as $field => [$title, $hint])
                        <label class="flex cursor-pointer items-start justify-between gap-4 rounded-xl border border-slate-200 p-4 hover:border-amber-300">
                            <span>
                                <span class="block text-sm font-bold text-slate-800">{{ $title }}</span>
                                <span class="text-xs text-slate-500">{{ $hint }}</span>
                            </span>
                            <input type="hidden" name="{{ $field }}" value="0">
                            <input type="checkbox" id="{{ $field }}" name="{{ $field }}" value="1" @checked($on($field)) class="peer sr-only">
                            <span class="relative mt-0.5 h-6 w-11 flex-shrink-0 rounded-full bg-slate-300 transition after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition peer-checked:bg-emerald-500 peer-checked:after:translate-x-5"></span>
                        </label>
                    @endforeach
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="{{ $label }}" for="sitemap_priority">Sitemap Priority</label>
                            <select id="sitemap_priority" name="sitemap_priority" class="{{ $input }} {{ $border('sitemap_priority') }} bg-white">
                                @for($p = 10; $p >= 0; $p--)
                                    @php $val = number_format($p / 10, 1); @endphp
                                    <option value="{{ $val }}" @selected(number_format((float) $v('sitemap_priority'), 1) === $val)>{{ $val }}{{ $p === 10 ? ' (highest)' : ($p === 5 ? ' (default)' : '') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="{{ $label }}" for="sitemap_changefreq">Change Frequency</label>
                            <select id="sitemap_changefreq" name="sitemap_changefreq" class="{{ $input }} {{ $border('sitemap_changefreq') }} bg-white">
                                @foreach(\App\Models\SeoPage::CHANGEFREQS as $freq)
                                    <option value="{{ $freq }}" @selected($v('sitemap_changefreq') === $freq)>{{ ucfirst($freq) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="rounded-xl bg-slate-900 p-4 font-mono text-xs text-emerald-300">
                        &lt;meta name="robots" content="<span id="robotsPreview"></span>"&gt;
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-24">
                    <h3 class="flex items-center gap-2 text-sm font-black text-slate-900"><i class="fa-solid fa-shield-halved text-blue-600"></i> Live SEO Health</h3>
                    <div class="mt-4 flex items-center justify-between">
                        <span id="healthBadge" class="rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase"></span>
                        <span class="text-xs font-bold text-slate-700"><span id="healthScore">0</span>% Score</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                        <div id="healthBar" class="h-full rounded-full transition-all duration-300" style="width:0"></div>
                    </div>
                    <p class="mt-5 text-[10px] font-bold uppercase tracking-wider text-slate-400">Checklist</p>
                    <ul id="healthChecklist" class="mt-2 space-y-2 text-xs"></ul>

                    <div class="mt-6 border-t border-slate-100 pt-5">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Google Preview</p>
                        <div class="mt-2 rounded-xl border border-slate-100 p-3">
                            <p id="serpUrl" class="truncate text-[11px] text-slate-500"></p>
                            <p id="serpTitle" class="mt-0.5 line-clamp-1 text-sm font-medium text-[#1a0dab]"></p>
                            <p id="serpDescription" class="mt-0.5 line-clamp-2 text-xs leading-5 text-slate-600"></p>
                        </div>
                    </div>

                    <div class="mt-5">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Social Share Preview</p>
                        <div class="mt-2 overflow-hidden rounded-xl border border-slate-200">
                            <div class="flex aspect-[1.91/1] items-center justify-center bg-slate-100">
                                <img id="socialImage" src="" alt="" class="hidden h-full w-full object-cover">
                                <i id="socialImagePlaceholder" class="fa-regular fa-image text-2xl text-slate-300"></i>
                            </div>
                            <div class="bg-slate-50 p-3">
                                <p id="socialDomain" class="text-[10px] uppercase text-slate-400"></p>
                                <p id="socialTitle" class="line-clamp-1 text-sm font-bold text-slate-800"></p>
                                <p id="socialDescription" class="line-clamp-2 text-xs text-slate-500"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('dashboard.seo') }}" class="rounded-xl border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-6 py-3 text-sm font-bold text-slate-900 shadow-sm transition hover:bg-amber-500">
                <i class="fa-solid fa-floppy-disk"></i> Save SEO Data
            </button>
            @if($isEdit)
                <a href="{{ $seoPage->public_url }}" target="_blank" rel="noopener" class="ml-auto text-xs font-bold text-blue-600 hover:underline">
                    View live page <i class="fa-solid fa-arrow-up-right-from-square ml-0.5"></i>
                </a>
            @endif
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
(() => {
    const form = document.getElementById('seoForm');
    const $ = (id) => document.getElementById(id);
    const val = (id) => ($(id)?.value || '').trim();
    const siteUrl = @json(rtrim(url('/'), '/'));
    const siteHost = @json(parse_url(url('/'), PHP_URL_HOST));
    const fallbackImage = @json($brandLogoUrl);
    const existingImages = { og: @json($seoPage->og_image_url), twitter: @json($seoPage->twitter_image_url) };
    const existingExternal = { og: @json((bool) $externalUrl($seoPage->og_image)), twitter: @json((bool) $externalUrl($seoPage->twitter_image)) };
    const uploadPreviews = { og: null, twitter: null };

    // ── Tabs ─────────────────────────────────────────────
    const tabs = document.querySelectorAll('.seo-tab');
    const panes = document.querySelectorAll('.seo-pane');
    function showTab(key) {
        tabs.forEach(tab => {
            const active = tab.dataset.tab === key;
            tab.classList.toggle('border-amber-500', active);
            tab.classList.toggle('text-amber-700', active);
            tab.classList.toggle('border-transparent', !active);
            tab.classList.toggle('text-slate-500', !active);
            tab.setAttribute('aria-selected', active);
        });
        panes.forEach(pane => pane.classList.toggle('hidden', pane.dataset.pane !== key));
        try { sessionStorage.setItem('seo_form_tab', key); } catch (e) {}
    }
    tabs.forEach(tab => tab.addEventListener('click', () => showTab(tab.dataset.tab)));

    // Open the first tab that has a validation error and mark every tab that has one.
    let firstErrorTab = null;
    panes.forEach(pane => {
        if (pane.querySelector('.border-red-400, .text-red-600')) {
            firstErrorTab ??= pane.dataset.pane;
            document.querySelector(`.seo-tab[data-tab="${pane.dataset.pane}"] .tab-error-dot`)?.classList.remove('hidden');
        }
    });
    let savedTab = null;
    try { savedTab = @json($isEdit) ? sessionStorage.getItem('seo_form_tab') : null; } catch (e) {}
    showTab(firstErrorTab || savedTab || 'basic');

    // ── Character counters ───────────────────────────────
    document.querySelectorAll('[data-counter]').forEach(counter => {
        const field = $(counter.dataset.counter);
        const max = Number(counter.dataset.max);
        const update = () => {
            const length = field.value.length;
            counter.textContent = `${length} / ${max}`;
            counter.classList.toggle('text-red-500', length > max);
            counter.classList.toggle('text-slate-400', length <= max);
        };
        field.addEventListener('input', update);
        update();
    });

    // ── Image inputs ─────────────────────────────────────
    ['og', 'twitter'].forEach(prefix => {
        const file = $(`${prefix}_image_file`);
        const remove = $(`remove_${prefix}_image`);
        file?.addEventListener('change', () => {
            if (uploadPreviews[prefix]) URL.revokeObjectURL(uploadPreviews[prefix]);
            uploadPreviews[prefix] = file.files[0] ? URL.createObjectURL(file.files[0]) : null;
            if (file.files[0] && remove) remove.checked = false;
            refresh();
        });
        remove?.addEventListener('change', refresh);
    });
    // Mirrors SeoPageController::resolveImage(): upload > remove > pasted URL > stored upload.
    function imageFor(prefix) {
        if (uploadPreviews[prefix]) return uploadPreviews[prefix];
        if ($(`remove_${prefix}_image`)?.checked) return null;
        if (val(`${prefix}_image_url`)) return val(`${prefix}_image_url`);
        return existingExternal[prefix] ? null : existingImages[prefix];
    }

    // ── Schema helpers ───────────────────────────────────
    function schemaState() {
        const raw = val('schema_json');
        if (!raw) return { valid: true, message: 'No schema — optional.' };
        try {
            const parsed = JSON.parse(raw);
            if (typeof parsed !== 'object' || parsed === null) throw new Error('Must be an object or array');
            const blocks = Array.isArray(parsed) ? parsed : [parsed];
            const types = blocks.map(b => b && b['@type']).filter(Boolean).join(', ');
            return { valid: true, message: `Valid JSON${types ? ' · ' + types : ''}` };
        } catch (e) {
            return { valid: false, message: 'Invalid JSON: ' + e.message };
        }
    }
    $('schemaFormat').addEventListener('click', () => {
        try { $('schema_json').value = JSON.stringify(JSON.parse(val('schema_json')), null, 2); refresh(); } catch (e) {}
    });
    document.querySelectorAll('[data-schema-template]').forEach(button => button.addEventListener('click', () => {
        const pageUrl = val('canonical_url') || siteUrl + (val('route_path') === '/' ? '' : val('route_path'));
        const name = val('meta_title') || val('page_name') || 'Porville';
        const description = val('meta_description');
        const templates = {
            WebPage: { '@context': 'https://schema.org', '@type': 'WebPage', name, description, url: pageUrl },
            Organization: { '@context': 'https://schema.org', '@type': 'Organization', name: 'Porville', url: siteUrl, logo: fallbackImage, sameAs: [] },
            LocalBusiness: { '@context': 'https://schema.org', '@type': 'LocalBusiness', name: 'Porville', url: siteUrl, image: fallbackImage, telephone: '', priceRange: '₹₹',
                address: { '@type': 'PostalAddress', streetAddress: '', addressLocality: '', addressRegion: '', postalCode: '', addressCountry: 'IN' },
                openingHours: 'Mo-Su 07:00-21:00' },
            BreadcrumbList: { '@context': 'https://schema.org', '@type': 'BreadcrumbList', itemListElement: [
                { '@type': 'ListItem', position: 1, name: 'Home', item: siteUrl },
                { '@type': 'ListItem', position: 2, name: val('page_name') || 'Page', item: pageUrl } ] },
            FAQPage: { '@context': 'https://schema.org', '@type': 'FAQPage', mainEntity: [
                { '@type': 'Question', name: 'Question here?', acceptedAnswer: { '@type': 'Answer', text: 'Answer here.' } } ] },
        };
        const current = val('schema_json');
        if (current && !confirm('Replace the current schema with this template?')) return;
        $('schema_json').value = JSON.stringify(templates[button.dataset.schemaTemplate], null, 2);
        refresh();
    }));

    // ── Live health + previews ───────────────────────────
    function refresh() {
        const title = val('meta_title');
        const description = val('meta_description');
        const keywords = val('meta_keywords').split(',').map(k => k.trim()).filter(Boolean);
        const canonical = val('canonical_url');
        const schema = schemaState();
        const ogImage = imageFor('og');
        const indexable = $('robots_index').checked;

        let canonicalValid = true;
        if (canonical) { try { new URL(canonical); } catch (e) { canonicalValid = false; } }

        const checks = [
            ['Title length optimal', title.length >= 50 && title.length <= 60],
            ['Description length optimal', description.length >= 120 && description.length <= 160],
            ['Keywords added', keywords.length > 0],
            ['Canonical URL set', canonicalValid],
            ['Open Graph configured', !!val('og_title') && !!val('og_description') && !!ogImage],
            ['Valid Schema JSON', schema.valid],
            ['Page is indexable', indexable],
        ];
        const passed = checks.filter(([, ok]) => ok).length;
        const score = Math.round(passed / checks.length * 100);
        const grade = score >= 80 ? ['Good', 'bg-emerald-100 text-emerald-700', 'bg-emerald-500']
                    : score >= 50 ? ['Average', 'bg-amber-100 text-amber-700', 'bg-amber-500']
                    : ['Poor', 'bg-red-100 text-red-700', 'bg-red-500'];

        $('healthScore').textContent = score;
        $('healthBadge').textContent = grade[0];
        $('healthBadge').className = 'rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase ' + grade[1];
        $('healthBar').className = 'h-full rounded-full transition-all duration-300 ' + grade[2];
        $('healthBar').style.width = score + '%';
        $('healthChecklist').innerHTML = checks.map(([label, ok]) => `
            <li class="flex items-center gap-2 ${ok ? 'text-slate-700' : 'text-orange-600'}">
                <i class="fa-solid ${ok ? 'fa-circle-check text-emerald-500' : 'fa-circle-xmark text-orange-500'}"></i>${label}
            </li>`).join('');

        // Keyword chips
        $('keywordChips').replaceChildren(...keywords.map(k => {
            const chip = document.createElement('span');
            chip.className = 'rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-semibold text-amber-800';
            chip.textContent = k;
            return chip;
        }));

        // Canonical placeholder follows the route path
        const path = val('route_path');
        const pageUrl = siteUrl + (!path || path === '/' ? '/' : '/' + path.replace(/^\/+/, ''));
        $('canonical_url').placeholder = pageUrl;

        // SERP
        const serpTitle = title || val('page_name') || 'Page title';
        $('serpUrl').textContent = (canonical || pageUrl).replace(/^https?:\/\//, '').replace(/\//g, ' › ').replace(/ › $/, '');
        $('serpTitle').textContent = serpTitle.length > 60 ? serpTitle.slice(0, 57) + '…' : serpTitle;
        $('serpDescription').textContent = description
            ? (description.length > 160 ? description.slice(0, 157) + '…' : description)
            : 'Add a meta description — otherwise Google picks random text from the page.';

        // Social
        const socialImage = ogImage || fallbackImage;
        $('socialImage').src = socialImage;
        $('socialImage').classList.toggle('hidden', !socialImage);
        $('socialImagePlaceholder').classList.toggle('hidden', !!socialImage);
        $('socialDomain').textContent = siteHost;
        $('socialTitle').textContent = val('og_title') || serpTitle;
        $('socialDescription').textContent = val('og_description') || description;

        // Schema status
        $('schemaStatus').textContent = schema.message;
        $('schemaStatus').className = 'text-xs font-semibold ' + (val('schema_json') ? (schema.valid ? 'text-emerald-600' : 'text-red-600') : 'text-slate-400');

        // Robots
        $('robotsPreview').textContent = (indexable ? 'index' : 'noindex') + ', ' + ($('robots_follow').checked ? 'follow' : 'nofollow');
    }

    form.addEventListener('input', refresh);
    form.addEventListener('change', refresh);
    refresh();
})();
</script>
@endsection
