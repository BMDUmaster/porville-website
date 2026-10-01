@extends('layouts.dashboard')
@section('title', 'Category Page Content')
@section('page_title', 'SEO Management')

@section('content')
<div class="mx-auto max-w-6xl space-y-5 p-4 md:p-6">

    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <a href="{{ route('dashboard.seo') }}" class="text-xs font-bold text-slate-500 hover:text-amber-700"><i class="fa-solid fa-arrow-left mr-1"></i> SEO Management</a>
            <h2 class="mt-1 text-2xl font-black text-slate-900">Category Page Content</h2>
            <p class="mt-1 text-sm text-slate-500">Write SEO text with HTML tags for each category or sub category page. It shows on that page just above the footer.</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">Category Page</th>
                    <th class="px-4 py-3">Heading</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Updated</th>
                    <th class="px-4 py-3 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($categories as $category)
                    @foreach(collect([$category])->concat($category->children) as $row)
                        @php
                            $content = $contents->get($row->id);
                            $isChild = $row->parent_id !== null;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <span class="{{ $isChild ? 'pl-6 text-slate-600' : 'font-bold text-slate-800' }}">
                                    @if($isChild)<i class="fa-solid fa-turn-up fa-rotate-90 mr-2 text-[10px] text-slate-300"></i>@endif{{ $row->name }}
                                </span>
                                @unless($row->is_active)
                                    <span class="ml-2 rounded bg-slate-100 px-1.5 text-[10px] font-bold text-slate-500">Inactive category</span>
                                @endunless
                            </td>
                            <td class="max-w-[260px] truncate px-4 py-3 text-slate-600">{{ $content->heading ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if(! $content || ! $content->content)
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-500">No content</span>
                                @elseif($content->is_active)
                                    <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-bold text-emerald-700">Live</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-700">Hidden</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">{{ $content?->updated_at?->format('d M Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('dashboard.seo.category-content.edit', $row) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 px-3 py-1.5 text-xs font-bold text-amber-700 hover:bg-amber-100">
                                    <i class="fa-regular fa-pen-to-square"></i> {{ $content && $content->content ? 'Edit' : 'Add' }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @empty
                    <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
