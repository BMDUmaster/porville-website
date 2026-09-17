@extends('layouts.dashboard')
@section('title', 'Contact Message')
@section('page_title', 'Contact Message')

@section('content')
<div class="p-4 sm:p-6">
    <div class="mb-4">
        <a href="{{ route('dashboard.contact-messages') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 px-4 py-2 text-sm font-bold text-slate-600 hover:bg-slate-200">
            <i class="fa-solid fa-arrow-left text-xs"></i> Back
        </a>
    </div>

    <div class="rounded-2xl border bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 border-b pb-5 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-green-600">{{ $message->department }}</p>
                <h2 class="mt-2 text-2xl font-extrabold text-slate-900">{{ $message->name }}</h2>
                <a href="mailto:{{ $message->email }}" class="mt-1 inline-flex text-sm font-semibold text-amber-600">{{ $message->email }}</a>
                <p class="mt-2 text-sm text-slate-400">{{ $message->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <span class="w-fit rounded-full px-3 py-1 text-xs font-bold {{ $message->read_at ? 'bg-slate-100 text-slate-500' : 'bg-green-100 text-green-700' }}">
                {{ $message->read_at ? 'Read' : 'Unread' }}
            </span>
        </div>

        <div class="py-6">
            <p class="whitespace-pre-line text-[15px] leading-8 text-slate-700">{{ $message->message }}</p>
        </div>

        <div class="flex flex-wrap gap-3 border-t pt-5">
            <a href="mailto:{{ $message->email }}" class="rounded-xl bg-amber-600 px-5 py-3 text-sm font-bold text-white hover:bg-amber-700">
                Reply by Email
            </a>
            <form method="POST" action="{{ route('dashboard.contact-messages.destroy', $message) }}" onsubmit="return confirm('Delete this message?')">
                @csrf @method('DELETE')
                <button type="submit" class="rounded-xl bg-red-50 px-5 py-3 text-sm font-bold text-red-600 hover:bg-red-100">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
