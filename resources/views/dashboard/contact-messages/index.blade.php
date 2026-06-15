@extends('layouts.dashboard')
@section('title', 'Contact Messages')
@section('page_title', 'Contact Us Messages')

@section('content')
<div class="p-4 sm:p-6">
    <div class="mb-4 grid gap-4 sm:grid-cols-2">
        <div class="rounded-xl border bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Total Messages</p>
            <p id="contactTotalCount" class="mt-2 text-3xl font-extrabold text-slate-900">{{ $stats['total'] }}</p>
        </div>
        <div class="rounded-xl border bg-white p-5">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Unread</p>
            <p id="contactUnreadCount" class="mt-2 text-3xl font-extrabold text-green-600">{{ $stats['unread'] }}</p>
        </div>
    </div>

    <div class="mb-4 flex flex-col gap-3 rounded-xl border bg-white p-4 md:flex-row md:items-center">
        <form method="GET" class="relative w-full flex-1">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search contact messages..."
                   class="w-full rounded-lg border py-2.5 pl-9 pr-4 text-sm outline-none focus:border-blue-400">
            <input type="hidden" name="status" value="{{ request('status') }}">
        </form>
        <a href="{{ route('dashboard.contact-messages') }}"
           class="rounded-xl px-4 py-2.5 text-center text-sm font-bold {{ request('status') ? 'bg-slate-100 text-slate-600' : 'bg-blue-600 text-white' }}">
            All
        </a>
        <a href="{{ route('dashboard.contact-messages', ['status' => 'unread']) }}"
           class="rounded-xl px-4 py-2.5 text-center text-sm font-bold {{ request('status') === 'unread' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-600' }}">
            Unread
        </a>
        <div class="inline-flex items-center justify-center gap-2 rounded-xl bg-green-50 px-4 py-2.5 text-xs font-bold uppercase tracking-[0.14em] text-green-700">
            <span id="contactLiveDot" class="h-2 w-2 rounded-full bg-green-500"></span>
            Live
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border bg-white">
        <table class="w-full min-w-[850px] text-left">
            <thead class="bg-gradient-to-r from-green-700 to-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-xs uppercase">Sr.No.</th>
                    <th class="px-4 py-3 text-xs uppercase">Sender</th>
                    <th class="px-4 py-3 text-xs uppercase">Department</th>
                    <th class="px-4 py-3 text-xs uppercase">Message</th>
                    <th class="px-4 py-3 text-xs uppercase">Date</th>
                    <th class="px-4 py-3 text-xs uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs uppercase">Actions</th>
                </tr>
            </thead>
            <tbody id="contactMessagesRows" class="divide-y divide-gray-100">
                @include('dashboard.contact-messages.partials.rows', ['messages' => $messages])
            </tbody>
        </table>
    </div>

    <div id="contactMessagesPagination" class="mt-4">{{ $messages->withQueryString()->links() }}</div>
</div>
@endsection

@section('scripts')
<script>
(() => {
    const totalNode = document.getElementById('contactTotalCount');
    const unreadNode = document.getElementById('contactUnreadCount');
    const rowsNode = document.getElementById('contactMessagesRows');
    const paginationNode = document.getElementById('contactMessagesPagination');
    const liveDot = document.getElementById('contactLiveDot');
    const liveUrl = @json(route('dashboard.contact-messages.live'));
    let isLoading = false;

    function setLiveState(ok) {
        liveDot.classList.toggle('bg-green-500', ok);
        liveDot.classList.toggle('bg-red-500', !ok);
    }

    function refreshContactMessages() {
        if (isLoading || document.hidden) {
            return;
        }

        isLoading = true;
        const url = new URL(liveUrl, window.location.origin);
        const currentParams = new URLSearchParams(window.location.search);

        currentParams.forEach((value, key) => {
            url.searchParams.set(key, value);
        });

        fetch(url.toString(), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Live refresh failed');
                }

                return response.json();
            })
            .then((data) => {
                totalNode.textContent = data.total;
                unreadNode.textContent = data.unread;
                rowsNode.innerHTML = data.rows;
                paginationNode.innerHTML = data.pagination;
                setLiveState(true);
            })
            .catch(() => setLiveState(false))
            .finally(() => {
                isLoading = false;
            });
    }

    window.setInterval(refreshContactMessages, 5000);
    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) {
            refreshContactMessages();
        }
    });
})();
</script>
@endsection
