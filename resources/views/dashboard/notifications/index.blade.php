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
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-2xl overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b flex justify-between items-center">
            <h2 id="modalTitle" class="text-xl font-bold text-gray-800">New Notification</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form id="notifForm" method="POST" action="{{ route('dashboard.notifications.store') }}" class="p-6 space-y-6">
            @csrf
            <span id="methodField"></span>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Title</label>
                <input type="text" name="subject" id="notifTitle" placeholder="Enter Notification Title"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Message</label>
                <textarea name="message" id="notifMessage" rows="4" placeholder="Enter your message"
                          class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal()"
                        class="px-6 py-2 bg-slate-200 text-slate-700 rounded font-bold hover:bg-slate-300">Cancel</button>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700">Send Now</button>
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
    showModal();
}
function openEditModal(id, subject, message) {
    document.getElementById('modalTitle').innerText = 'Edit Notification';
    document.getElementById('notifTitle').value = subject;
    document.getElementById('notifMessage').value = message;
    document.getElementById('methodField').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('notifForm').action = '/notifications/' + id;
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
</script>
@endsection
