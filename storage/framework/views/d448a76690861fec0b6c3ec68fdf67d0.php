
<?php $__env->startSection('title', 'Notifications'); ?>
<?php $__env->startSection('page_title', 'Notification Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6 p-4 md:p-8">

    
    <form method="GET" class="flex justify-end">
        <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row sm:items-center">
            <label class="text-sm font-bold text-gray-600">Search:</label>
            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                   class="border rounded px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm">Go</button>
        </div>
    </form>

    
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
                <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-4 text-sm text-gray-600"><?php echo e($notifications->firstItem() + $i); ?></td>
                    <td class="px-4 py-4 text-sm text-gray-600"><?php echo e($notif->created_at->format('d-m-Y')); ?></td>
                    <td class="px-4 py-4 text-sm font-semibold text-gray-800"><?php echo e($notif->subject); ?></td>
                    <td class="px-4 py-4 text-sm text-gray-600 max-w-md truncate"><?php echo e($notif->message); ?></td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex flex-wrap justify-center gap-2">
                            <button onclick="openViewModal('<?php echo e(addslashes($notif->subject)); ?>', '<?php echo e(addslashes($notif->message)); ?>')"
                                    class="px-3 py-1 text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-lg">View</button>
                            <button onclick="openEditModal(<?php echo e($notif->id); ?>, '<?php echo e(addslashes($notif->subject)); ?>', '<?php echo e(addslashes($notif->message)); ?>')"
                                    class="px-3 py-1 text-xs font-bold text-amber-600 hover:bg-amber-50 rounded-lg">Edit</button>
                            <form method="POST" action="<?php echo e(route('dashboard.notifications.destroy', $notif)); ?>"
                                  onsubmit="return confirm('Delete this notification?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="px-3 py-1 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">No notifications found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div><?php echo e($notifications->withQueryString()->links()); ?></div>
        <button onclick="openAddModal()"
                class="w-full rounded-lg bg-blue-600 px-6 py-2 font-bold text-white transition shadow-md hover:bg-blue-700 sm:w-auto">
            Send New Notification
        </button>
    </div>
</div>


<div id="notifModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4 backdrop-blur-sm">
    <div class="max-h-[92vh] w-full max-w-[920px] overflow-y-auto rounded-[18px] bg-white shadow-[0_24px_70px_rgba(15,23,42,0.25)]">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-5 sm:px-8 sm:py-7">
            <h2 id="modalTitle" class="text-[22px] font-extrabold tracking-tight text-slate-800">New Notification</h2>
            <button onclick="closeModal()" class="text-4xl font-light leading-none text-slate-300 transition hover:text-slate-500">&times;</button>
        </div>
        <form id="notifForm" method="POST" action="<?php echo e(route('dashboard.notifications.store')); ?>">
            <?php echo csrf_field(); ?>
            <span id="methodField"></span>
            <div class="space-y-7 px-5 py-5 sm:space-y-9 sm:px-8 sm:py-8">
                <div id="userSelectSection" class="space-y-4">
                    <label class="block text-[15px] font-bold text-slate-700">Select Customers</label>
                    <input type="text" id="userSearchInput" placeholder="Search customer by name or ID..."
                           class="w-full rounded-[10px] border border-slate-300 px-5 py-3 text-base text-slate-700 outline-none transition focus:border-blue-500">
                    <label class="flex items-center gap-3 text-[15px] font-medium text-slate-600">
                        <input type="checkbox" id="selectAllUsers"
                               class="h-6 w-6 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>Select All Customers</span>
                    </label>
                    <div id="userListBox" class="max-h-56 overflow-y-auto rounded-[12px] border border-slate-200 bg-slate-50/70 p-3">
                        <div class="grid gap-2">
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <label class="user-option flex items-center gap-3 rounded-xl border border-transparent bg-white px-3 py-2.5 text-sm text-slate-700 transition hover:border-blue-200 hover:bg-blue-50/60"
                                       data-search="<?php echo e(strtolower($user->name . ' ' . $user->email . ' ' . $user->id)); ?>">
                                    <input type="checkbox" name="user_ids[]" value="<?php echo e($user->id); ?>"
                                           class="user-checkbox h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="min-w-0">
                                        <span class="block truncate font-semibold text-slate-800"><?php echo e($user->name); ?></span>
                                        <span class="block truncate text-xs text-slate-400">#<?php echo e($user->id); ?> · <?php echo e($user->email); ?></span>
                                    </span>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="rounded-xl border border-dashed border-slate-200 bg-white px-4 py-6 text-center text-sm text-slate-400">
                                    No active customers available right now.
                                </div>
                            <?php endif; ?>
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
            <div class="flex flex-col gap-3 border-t border-slate-200 bg-slate-50/70 px-5 py-5 sm:flex-row sm:justify-end sm:gap-4 sm:px-8">
                <button type="button" onclick="closeModal()"
                        class="w-full rounded-[10px] bg-slate-200 px-6 py-3 text-[15px] font-bold text-slate-700 transition hover:bg-slate-300 sm:min-w-44 sm:w-auto">Cancel</button>
                <button type="submit" id="submitNotifButton"
                        class="w-full rounded-[10px] bg-blue-600 px-6 py-3 text-[15px] font-bold text-white transition hover:bg-blue-700 sm:min-w-44 sm:w-auto">Send Now</button>
            </div>
        </form>
    </div>
</div>


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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function openAddModal() {
    document.getElementById('modalTitle').innerText = 'New Notification';
    document.getElementById('notifTitle').value = '';
    document.getElementById('notifMessage').value = '';
    document.getElementById('methodField').innerHTML = '';
    document.getElementById('notifForm').action = '<?php echo e(route("dashboard.notifications.store")); ?>';
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
    document.getElementById('notifForm').action = '<?php echo e(url("/notifications")); ?>/' + id;
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/dashboard/notifications/index.blade.php ENDPATH**/ ?>