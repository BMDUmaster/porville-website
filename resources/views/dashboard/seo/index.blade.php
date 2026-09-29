@extends('layouts.dashboard')
@section('title', 'SEO Management')
@section('page_title', 'SEO Management')

@section('content')
<div class="p-4 md:p-6 mx-auto max-w-7xl space-y-5">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h2 class="text-2xl font-black text-slate-900">SEO Management</h2>
            <p class="mt-1 text-sm text-slate-500">Control meta tags, social previews, schema and crawl rules for every public page.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if($missingPages > 0)
                <form method="POST" action="{{ route('dashboard.seo.import') }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm transition hover:border-amber-400 hover:text-amber-700">
                        <i class="fa-solid fa-file-import"></i> Import {{ $missingPages }} {{ Str::plural('page', $missingPages) }}
                    </button>
                </form>
            @endif
            <a href="{{ route('dashboard.seo.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-5 py-2.5 text-sm font-bold text-slate-900 shadow-sm transition hover:bg-amber-500">
                <i class="fa-solid fa-plus"></i> Add New Page
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php
            $cards = [
                ['label' => 'Total Pages', 'value' => $stats['total'], 'icon' => 'fa-file-lines', 'tone' => 'bg-slate-100 text-slate-700'],
                ['label' => 'Active', 'value' => $stats['active'], 'icon' => 'fa-circle-check', 'tone' => 'bg-emerald-50 text-emerald-600'],
                ['label' => 'Average Score', 'value' => $stats['average'] . '%', 'icon' => 'fa-gauge-high', 'tone' => 'bg-amber-50 text-amber-600'],
                ['label' => 'Needs Attention', 'value' => $stats['attention'], 'icon' => 'fa-triangle-exclamation', 'tone' => 'bg-red-50 text-red-600'],
            ];
        @endphp
        @foreach($cards as $card)
            <div class="stat-card flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <div class="icon-box flex h-11 w-11 items-center justify-center rounded-xl {{ $card['tone'] }}">
                    <i class="fa-solid {{ $card['icon'] }}"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $card['label'] }}</p>
                    <p class="text-xl font-black text-slate-900">{{ $card['value'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Crawler files --}}
    <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 text-sm shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <p class="text-slate-600"><i class="fa-solid fa-robot mr-2 text-amber-600"></i>Search engines read these automatically — submit the sitemap in Google Search Console.</p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('seo.sitemap') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200">
                <i class="fa-solid fa-sitemap"></i> sitemap.xml
            </a>
            <a href="{{ route('seo.robots') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-slate-200">
                <i class="fa-solid fa-file-code"></i> robots.txt
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('dashboard.seo') }}" class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row">
        <div class="relative flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
            <input type="text" name="q" value="{{ $search }}" placeholder="Search by page name, route or title…"
                   class="w-full rounded-xl border border-slate-200 py-2.5 pl-10 pr-4 text-sm outline-none focus:border-amber-500">
        </div>
        <select name="status" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm outline-none focus:border-amber-500">
            <option value="">All statuses</option>
            <option value="active" @selected($status === 'active')>Active</option>
            <option value="inactive" @selected($status === 'inactive')>Inactive</option>
        </select>
        <button type="submit" class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white hover:bg-black">Filter</button>
        @if($search !== '' || $status)
            <a href="{{ route('dashboard.seo') }}" class="rounded-xl bg-slate-100 px-5 py-2.5 text-center text-sm font-bold text-slate-600 hover:bg-slate-200">Reset</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs font-bold uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Page</th>
                        <th class="px-5 py-3">Meta Title</th>
                        <th class="px-5 py-3">SEO Score</th>
                        <th class="px-5 py-3">Crawl</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pages as $page)
                        @php
                            $score = $page->seo_score;
                            $grade = \App\Models\SeoPage::gradeFor($score);
                        @endphp
                        <tr class="hover:bg-slate-50/60">
                            <td class="px-5 py-4">
                                <p class="font-bold text-slate-900">{{ $page->page_name }}</p>
                                <a href="{{ $page->public_url }}" target="_blank" rel="noopener" class="text-xs font-medium text-blue-600 hover:underline">
                                    {{ $page->route_path }} <i class="fa-solid fa-arrow-up-right-from-square ml-0.5 text-[9px]"></i>
                                </a>
                            </td>
                            <td class="max-w-xs px-5 py-4">
                                @if($page->meta_title)
                                    <p class="truncate font-medium text-slate-700" title="{{ $page->meta_title }}">{{ $page->meta_title }}</p>
                                    <p class="truncate text-xs text-slate-400">{{ Str::limit($page->meta_description, 70) ?: 'No description' }}</p>
                                @else
                                    <span class="text-xs italic text-slate-400">Not set</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="rounded-full px-2 py-0.5 text-[10px] font-bold uppercase {{ $grade['class'] }}">{{ $grade['label'] }}</span>
                                    <span class="text-xs font-bold text-slate-700">{{ $score }}%</span>
                                </div>
                                <div class="mt-1.5 h-1.5 w-28 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $grade['bar'] }}" style="width: {{ $score }}%"></div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="block text-xs font-semibold {{ $page->robots_index ? 'text-emerald-600' : 'text-red-500' }}">{{ $page->robots_content }}</span>
                                <span class="text-[11px] text-slate-400">{{ $page->sitemap_include ? 'In sitemap · ' . number_format($page->sitemap_priority, 1) : 'Not in sitemap' }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('dashboard.seo.toggle', $page) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="Click to {{ $page->is_active ? 'deactivate' : 'activate' }}"
                                            class="rounded-full px-3 py-1 text-xs font-bold transition {{ $page->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        {{ $page->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('dashboard.seo.edit', $page) }}" title="Edit"
                                       class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 hover:bg-amber-50 hover:text-amber-700">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form method="POST" action="{{ route('dashboard.seo.destroy', $page) }}" onsubmit="return confirm('Delete SEO data for {{ addslashes($page->page_name) }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Delete" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-400 hover:bg-red-50 hover:text-red-600">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-14 text-center">
                                <i class="fa-solid fa-magnifying-glass-chart mb-3 text-3xl text-slate-300"></i>
                                <p class="text-sm font-semibold text-slate-500">No SEO pages found.</p>
                                <p class="mt-1 text-xs text-slate-400">Use “Import pages” to pull in every storefront page, or add one manually.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pages->hasPages())
            <div class="border-t border-slate-100 px-5 py-3">{{ $pages->links() }}</div>
        @endif
    </div>
</div>
@endsection
