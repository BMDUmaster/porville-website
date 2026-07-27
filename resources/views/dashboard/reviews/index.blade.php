@extends('layouts.dashboard')
@section('title', 'Reviews')
@section('page_title', 'Customer Reviews')

@section('content')
<div class="p-4 md:p-6">
    <div class="mb-5 grid grid-cols-2 gap-3 md:grid-cols-4">
        @foreach(['all' => 'All Reviews', 'pending' => 'Pending', 'approved' => 'Approved', 'hidden' => 'Hidden'] as $key => $label)
            <a href="{{ route('dashboard.reviews', $key === 'all' ? [] : ['status' => $key]) }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-bold uppercase text-slate-400">{{ $label }}</p>
                <p class="mt-2 text-2xl font-black text-slate-900">{{ $counts[$key] }}</p>
            </a>
        @endforeach
    </div>

    <form class="mb-5 flex flex-wrap gap-3 rounded-2xl border border-slate-200 bg-white p-4">
        <select name="status" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm">
            <option value="">All statuses</option>
            @foreach(\App\Models\Review::STATUSES as $status)<option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>@endforeach
        </select>
        <select name="rating" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm">
            <option value="">All ratings</option>
            @foreach(range(5, 1) as $rating)<option value="{{ $rating }}" {{ (int) request('rating') === $rating ? 'selected' : '' }}>{{ $rating }} Stars</option>@endforeach
        </select>
        <button class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-bold text-white">Filter</button>
    </form>

    <div class="space-y-4">
        @forelse($reviews as $review)
            <article class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div class="min-w-0">
                        <div class="text-xl text-yellow-400">{{ str_repeat('★', $review->rating) }}<span class="text-slate-200">{{ str_repeat('★', 5 - $review->rating) }}</span></div>
                        <h2 class="mt-2 font-black text-slate-900">{{ $review->user?->name ?? $review->order?->shipping_address['name'] ?? 'Customer' }}</h2>
                        <p class="mt-1 text-xs text-slate-500">Order {{ $review->order?->order_number ?: '#'.$review->order_id }} · {{ $review->created_at->format('d M Y, h:i A') }}</p>
                        <p class="mt-3 text-sm leading-6 text-slate-700">{{ $review->comment ?: 'No written comment.' }}</p>
                        <p class="mt-3 text-xs font-semibold text-slate-400">Product: <span class="text-slate-700">{{ $review->product?->name ?? 'Not selected' }}</span></p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <form method="POST" action="{{ route('dashboard.reviews.update', $review) }}" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="status" class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-bold">
                                @foreach(\App\Models\Review::STATUSES as $status)<option value="{{ $status }}" {{ $review->status === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>@endforeach
                            </select>
                            <select name="display_on" class="rounded-xl border border-slate-300 px-3 py-2 text-xs font-bold">
                                <option value="home" {{ $review->display_on === 'home' ? 'selected' : '' }}>Home Page</option>
                                <option value="product" {{ $review->display_on === 'product' ? 'selected' : '' }}>Product Page</option>
                            </select>
                            <button class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white">Update</button>
                        </form>
                        <form method="POST" action="{{ route('dashboard.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                            @csrf @method('DELETE')
                            <button class="rounded-xl bg-red-50 px-4 py-2 text-xs font-bold text-red-600">Delete</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center text-slate-400">No reviews found.</div>
        @endforelse
    </div>
    <div class="mt-5">{{ $reviews->links() }}</div>
</div>
@endsection
