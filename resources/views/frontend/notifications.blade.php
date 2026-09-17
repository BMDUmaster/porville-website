@extends('frontend.layouts.app')
@section('title', 'Notifications')

@section('content')
<section class="bg-[#faf7f0] py-10 md:py-14">
    <div class="mx-auto max-w-3xl px-4">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <a href="{{ route('frontend.profile') }}" class="text-xs font-bold uppercase tracking-[0.14em] text-amber-700 hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> Back to Profile</a>
                <h1 class="font-classic mt-2 text-2xl font-bold text-slate-900">Notifications</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $totalNotifications }} message(s) from the Porville team.</p>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.12em] text-slate-600">
                    {{ $totalNotifications }} Total
                </span>
                @if($unreadNotifications > 0)
                    <span class="rounded-full bg-red-500 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.12em] text-white">
                        {{ $unreadNotifications }} New
                    </span>
                    <form method="POST" action="{{ route('frontend.notifications.read') }}">
                        @csrf
                        <button type="submit" class="text-[10px] font-black uppercase tracking-[0.14em] text-amber-700 hover:underline">
                            Mark all read
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if($userNotifications->count())
            <div class="space-y-3">
                @foreach($userNotifications as $notification)
                    <div class="flex flex-col rounded-2xl border {{ $notification->read_at ? 'border-slate-200 bg-white' : 'border-amber-200 bg-amber-50/50' }} px-5 py-4 shadow-sm">
                        <div class="flex items-start justify-between gap-3">
                            <p class="text-sm font-bold text-slate-900">{{ $notification->subject }}</p>
                            @if(! $notification->read_at && $notification->recipient_id)
                                <span class="rounded-full bg-amber-600 px-2 py-0.5 text-[9px] font-black uppercase tracking-[0.12em] text-white">New</span>
                            @endif
                        </div>
                        <p class="mt-2 whitespace-pre-wrap text-sm leading-6 text-slate-600">{{ $notification->message }}</p>
                        <p class="mt-3 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">
                            {{ $notification->created_at->format('d M Y, h:i A') }}
                        </p>
                        @if($notification->recipient_id === $user->id)
                            <div class="mt-3 flex flex-wrap items-center gap-2 border-t border-slate-200/80 pt-3">
                                @if(! $notification->read_at)
                                    <form method="POST" action="{{ route('frontend.notifications.mark-read', $notification) }}">
                                        @csrf
                                        <button type="submit" class="rounded-lg border border-amber-200 bg-white px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.12em] text-amber-700 transition hover:bg-amber-50">
                                            Mark as Read
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('frontend.notifications.delete', $notification) }}" onsubmit="return confirm('Delete this notification?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-red-200 bg-white px-3 py-1.5 text-[10px] font-black uppercase tracking-[0.12em] text-red-600 transition hover:bg-red-50">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $userNotifications->links() }}
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-4 py-12 text-center">
                <i class="fa-regular fa-bell text-3xl text-slate-300"></i>
                <p class="mt-3 text-sm font-semibold text-slate-500">No notifications yet.</p>
            </div>
        @endif
    </div>
</section>
@endsection
