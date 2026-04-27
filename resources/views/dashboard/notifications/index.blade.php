@extends('layouts.dashboard')
@section('title', 'Notifications')
@section('page_title', 'Notification Management')

@section('content')
<div class="p-4 md:p-8 space-y-6">

    {{-- Search --}}
    <form method="GET" class="flex justify-end">
        <div class="flex items-center gap-2">
            <label class="text-sm font-bold text-gray-600">Search:</label>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="border rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm">Go</button>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm border overflow-x-auto">
        <table class="w-full text-left min-w-[700px]">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700 w-16">Sr.No</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase text-gray-700 w-36">Date</th>
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
                    <td class="px-4 py-4 text-sm font-semibold text-gray-800">{{ $notif->subject }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600 max-w-md truncate">{{ $notif->message }}</td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openViewModal('{{ addslashes($notif->subject) }}', '{{ addslashes($notif->message) }}')"
                                    class="px-3 py-1 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg">View</button>
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
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No notifications found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center">
        <div>{{ $notifications->withQueryString()->links() }}</div>
        <button onclick="openAddModal()"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
            Send New Notification
        </button>
    </div>
</div>

{{-- Add/Edit Modal --}}
<div id="notifModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="w-full max-w-[920px] overflow-hidden rounded-[18px] bg-white shadow-[0_24px_70px_rgba(15,23,42,0.25)]">
        <div class="flex items-center justify-between border-b border-slate-200 px-8 py-7">
            <h2 id="modalTitle" class="text-[22px] font-extrabold tracking-tight text-slate-800">New Notification</h2>
            <button onclick="closeModal()" class="text-4xl font-light leading-none text-slate-300 transition hover:text-slate-500">&times;</button>
        </div>
        <form id="notifForm" method="POST" action="{{ route('dashboard.notifications.store') }}">
            @csrf
            <span id="methodField"></span>
            <div class="space-y-9 px-8 py-8">
                <div id="userSelectSection" class="space-y-4">
                    <label class="block text-[15px] font-bold text-slate-700">Select Users</label>
                    <input type="text" id="userSearchInput" placeholder="Search user by name or ID..."
                           class="w-full rounded-[10px] border border-slate-300 px-5 py-3 text-base text-slate-700 outline-none transition focus:border-blue-500">
                    <label class="flex items-center gap-3 text-[15px] font-medium text-slate-600">
                        <input type="checkbox" id="selectAllUsers"
                               class="h-6 w-6 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>Select All Users</span>
                    </label>
                    <div id="userListBox" class="max-h-56 overflow-y-auto rounded-[12px] border border-slate-200 bg-slate-50/70 p-3">
                        <div class="grid gap-2">
                            @forelse($users as $user)
                                <label class="user-option flex items-center gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm text-slate-700 transition hover:border-blue-200 hover:bg-blue-50/60"
                                       data-search="{{ strtolower($user->name . ' ' . $user->email . ' ' . $user->id) }}">
                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                                           class="user-checkbox h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="min-w-0">
                                        <span class="block truncate font-semibold text-slate-800">{{ $user->name }}</span>
                                        <span class="block truncate text-xs text-slate-400">#{{ $user->id }} · {{ $user->email }}</span>
                                    </span>
                                </label>
                            @empty
                                <div class="rounded-xl border border-dashed border-slate-200 bg-white px-4 py-6 text-center text-sm text-slate-400">
                                    No active users available right now.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-[15px] font-bold text-slate-700">Title</label>
                    <input type="text" name="subject" id="notifTitle" placeholder="Enter Notification Title"
                           class="w-full rounded-[10px] border border-slate-300 px-5 py-3 text-base text-slate-700 outline-none transition focus:border-blue-500">
                </div>

                <div>
                    <label class="mb-2 block text-[15px] font-bold text-slate-700">Message</label>
                    <textarea name="message" id="notifMessage" rows="6" placeholder="Enter your message"
                              class="w-full rounded-[10px] border border-slate-300 px-5 py-4 text-base text-slate-700 outline-none transition focus:border-blue-500"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-4 border-t border-slate-200 bg-slate-50/70 px-8 py-5">
                <button type="button" onclick="closeModal()"
                        class="min-w-44 rounded-[10px] bg-slate-200 px-6 py-3 text-[15px] font-bold text-slate-700 transition hover:bg-slate-300">Cancel</button>
                <button type="submit" id="submitNotifButton"
                        class="min-w-44 rounded-[10px] bg-blue-600 px-6 py-3 text-[15px] font-bold text-white transition hover:bg-blue-700">Send Now</button>
            </div>
        </form>
    </div>
</div>

{{-- View Modal --}}
<div id="viewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white w-full max-w-lg rounded-lg shadow-2xl p-6">
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
    document.getElementById('userSelectSection').classList.remove('hidden');
    clearUserSelection();
    showModal();
}
function openEditModal(id, subject, message) {
    document.getElementById('modalTitle').innerText = 'Edit Notification';
    document.getElementById('notifTitle').value = subject;
    document.getElementById('notifMessage').value = message;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('notifForm').action = '{{ url("/notifications") }}/' + id;
    document.getElementById('submitNotifButton').innerText = 'Update Now';
    document.getElementById('userSelectSection').classList.add('hidden');
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

function clearUserSelection() {
    const selectAll = document.getElementById('selectAllUsers');
    const searchInput = document.getElementById('userSearchInput');
    const checkboxes = document.querySelectorAll('.user-checkbox');

    if (selectAll) {
        selectAll.checked = false;
    }

    if (searchInput) {
        searchInput.value = '';
    }

    checkboxes.forEach((checkbox) => {
        checkbox.checked = false;
    });

    filterUsers('');
}

function filterUsers(searchTerm) {
    const normalizedTerm = searchTerm.trim().toLowerCase();

    document.querySelectorAll('.user-option').forEach((option) => {
        const haystack = option.dataset.search || '';
        option.classList.toggle('hidden', normalizedTerm !== '' && !haystack.includes(normalizedTerm));
    });
}

document.getElementById('userSearchInput')?.addEventListener('input', function (event) {
    filterUsers(event.target.value);
});

document.getElementById('selectAllUsers')?.addEventListener('change', function (event) {
    document.querySelectorAll('.user-checkbox').forEach((checkbox) => {
        if (!checkbox.closest('.user-option')?.classList.contains('hidden')) {
            checkbox.checked = event.target.checked;
        }
    });
});
</script>
@endsection
