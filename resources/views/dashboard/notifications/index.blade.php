@extends('layouts.dashboard')
@section('title', 'Notifications')
@section('page_title', 'Notification Management')

@section('content')
<div class="space-y-6 p-4 md:p-8">

    {{-- Send button + Search --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <button onclick="openAddModal()"
                class="w-full rounded-lg bg-amber-600 px-6 py-2 font-bold text-white transition shadow-md hover:bg-amber-700 sm:w-auto">
            <i class="fa-solid fa-paper-plane mr-1.5"></i> Send New Notification
        </button>
        <form method="GET" action="{{ route('dashboard.notifications') }}" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
            <label class="text-sm font-bold text-gray-600">Search:</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Customer, email, subject or message"
                   class="border rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-amber-500">
            <button type="submit" class="bg-amber-600 text-white px-4 py-1.5 rounded text-sm">Go</button>
            @if(request()->filled('search'))
                <a href="{{ route('dashboard.notifications') }}" class="px-3 py-1.5 text-sm font-semibold text-slate-500 hover:text-slate-800">Clear</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-x-auto">
        <table class="w-full text-left min-w-[700px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700 w-16">Sr.No</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700 w-36">Date</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Customer</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Subject</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700">Message</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($notifications as $i => $notif)
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-4 text-sm text-gray-600">{{ $notifications->firstItem() + $i }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600">{{ $notif->created_at->format('d-m-Y') }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600">
                        @if($notif->recipient)
                            <p class="font-bold text-slate-800">{{ $notif->recipient->name }}</p>
                            <p class="text-xs text-slate-400">{{ $notif->recipient->email }}</p>
                        @else
                            <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[11px] font-bold text-amber-700">All Customers</span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm font-semibold text-gray-800">{{ $notif->subject }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600 max-w-md truncate">{{ $notif->message }}</td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex flex-wrap justify-center gap-2">
                            <button onclick="openViewModal('{{ addslashes($notif->subject) }}', '{{ addslashes($notif->message) }}')"
                                    class="px-3 py-1 text-xs font-bold text-amber-600 hover:bg-amber-50 rounded-lg">View</button>
                            <button onclick="openEditModal({{ $notif->id }}, '{{ addslashes($notif->subject) }}', '{{ addslashes($notif->message) }}')"
                                    class="px-3 py-1 text-xs font-bold text-amber-600 hover:bg-amber-50 rounded-lg">Edit</button>
                            <form method="POST" action="{{ route('dashboard.notifications.destroy', $notif) }}"
                                  onsubmit="return confirm('Delete this notification?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-1 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No notifications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination (20 per page) --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <p class="text-sm text-slate-500">
            @if($notifications->total())
                Showing {{ $notifications->firstItem() }}–{{ $notifications->lastItem() }} of {{ $notifications->total() }} notifications
            @endif
        </p>
        <div>{{ $notifications->withQueryString()->links() }}</div>
    </div>
</div>

{{-- Add/Edit Modal --}}
<div id="notifModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="max-h-[92vh] w-full max-w-[920px] overflow-y-auto rounded-[18px] bg-white shadow-[0_24px_70px_rgba(15,23,42,0.25)]">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-5 sm:px-8 sm:py-7">
            <h2 id="modalTitle" class="text-[22px] font-extrabold tracking-tight text-slate-800">New Notification</h2>
            <button onclick="closeModal()" class="text-4xl font-light leading-none text-slate-300 transition hover:text-slate-500">&times;</button>
        </div>
        <form id="notifForm" method="POST" action="{{ route('dashboard.notifications.store') }}" onsubmit="return confirmNotificationSend()">
            @csrf
            <span id="methodField"></span>
            <div class="space-y-7 px-5 py-5 sm:space-y-9 sm:px-8 sm:py-8">
                <div id="recipientInfo" class="flex items-center gap-3 rounded-[12px] border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <i class="fa-solid fa-users"></i>
                    <span>This notification will be sent to <strong>all {{ $customerCount }} active customer(s)</strong> — in-app and email.</span>
                </div>

                <div>
                    <label class="mb-2 block text-[15px] font-bold text-slate-700">Title</label>
                    <input type="text" name="subject" id="notifTitle" required maxlength="200" placeholder="Enter Notification Title"
                           class="w-full rounded-[10px] border border-slate-300 px-5 py-3 text-base text-slate-700 outline-none transition focus:border-amber-500">
                </div>

                <div>
                    <label class="mb-2 block text-[15px] font-bold text-slate-700">Message</label>
                    <textarea name="message" id="notifMessage" rows="6" required placeholder="Enter your message"
                              class="w-full rounded-[10px] border border-slate-300 px-5 py-4 text-base text-slate-700 outline-none transition focus:border-amber-500"></textarea>
                </div>
            </div>
            <div class="flex flex-col gap-3 border-t border-slate-200 bg-slate-50/70 px-5 py-5 sm:flex-row sm:justify-end sm:gap-4 sm:px-8">
                <button type="button" onclick="closeModal()"
                        class="w-full rounded-[10px] bg-slate-200 px-6 py-3 text-[15px] font-bold text-slate-700 transition hover:bg-slate-300 sm:min-w-44 sm:w-auto">Cancel</button>
                <button type="submit" id="submitNotifButton"
                        class="w-full rounded-[10px] bg-amber-600 px-6 py-3 text-[15px] font-bold text-white transition hover:bg-amber-700 sm:min-w-44 sm:w-auto">Send Now</button>
            </div>
        </form>
    </div>
</div>

{{-- View Modal --}}
<div id="viewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="w-full max-w-lg rounded-lg bg-white p-5 shadow-2xl sm:p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Notification Details</h2>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <p class="font-bold text-gray-800 mb-2" id="viewSubject"></p>
        <p class="text-gray-600 text-sm" id="viewMessage"></p>
        <div class="mt-4 flex justify-end">
            <button onclick="closeViewModal()" class="px-4 py-2 bg-gray-100 rounded font-bold text-sm">Close</button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openAddModal() {
    document.getElementById('modalTitle').innerText = 'New Notification';
    document.getElementById('notifTitle').value = '';
    document.getElementById('notifMessage').value = '';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('notifForm').action = '{{ route("dashboard.notifications.store") }}';
    document.getElementById('submitNotifButton').innerText = 'Send Now';
    document.getElementById('recipientInfo').classList.remove('hidden');
    showModal();
}
function openEditModal(id, subject, message) {
    document.getElementById('modalTitle').innerText = 'Edit Notification';
    document.getElementById('notifTitle').value = subject;
    document.getElementById('notifMessage').value = message;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('notifForm').action = '{{ url("/notifications") }}/' + id;
    document.getElementById('submitNotifButton').innerText = 'Update Now';
    document.getElementById('recipientInfo').classList.add('hidden');
    showModal();
}
function openViewModal(subject, message) {
    document.getElementById('viewSubject').innerText = subject;
    document.getElementById('viewMessage').innerText = message;
    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModal').classList.add('flex');
}
function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.getElementById('viewModal').classList.remove('flex');
}
function showModal() {
    document.getElementById('notifModal').classList.remove('hidden');
    document.getElementById('notifModal').classList.add('flex');
}
function closeModal() {
    document.getElementById('notifModal').classList.add('hidden');
    document.getElementById('notifModal').classList.remove('flex');
}

function confirmNotificationSend() {
    // Editing an existing notification does not send anything new.
    if (document.getElementById('recipientInfo').classList.contains('hidden')) {
        return true;
    }

    return confirm('Send this notification to all {{ $customerCount }} active customer(s)?');
}
</script>
@endsection
