<?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="transition hover:bg-gray-50">
        <td class="px-4 py-3 text-sm text-gray-500"><?php echo e($messages->firstItem() + $index); ?></td>
        <td class="px-4 py-3">
            <p class="text-sm font-bold text-slate-900"><?php echo e($message->name); ?></p>
            <a href="mailto:<?php echo e($message->email); ?>" class="text-xs font-semibold text-amber-600"><?php echo e($message->email); ?></a>
        </td>
        <td class="px-4 py-3 text-sm font-semibold text-slate-700"><?php echo e($message->department); ?></td>
        <td class="px-4 py-3">
            <p class="max-w-[280px] truncate text-sm text-slate-500"><?php echo e($message->message); ?></p>
        </td>
        <td class="px-4 py-3 text-sm text-slate-500"><?php echo e($message->created_at->format('d M Y, h:i A')); ?></td>
        <td class="px-4 py-3">
            <span class="rounded-full px-3 py-1 text-xs font-bold <?php echo e($message->read_at ? 'bg-slate-100 text-slate-500' : 'bg-green-100 text-green-700'); ?>">
                <?php echo e($message->read_at ? 'Read' : 'Unread'); ?>

            </span>
        </td>
        <td class="px-4 py-3">
            <div class="flex justify-center gap-2">
                <a href="<?php echo e(route('dashboard.contact-messages.show', $message)); ?>"
                   class="rounded-lg p-2 text-xs text-amber-600 hover:bg-amber-100">
                    <i class="fa-regular fa-eye"></i>
                </a>
                <?php if (! ($message->read_at)): ?>
                    <form method="POST" action="<?php echo e(route('dashboard.contact-messages.read', $message)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="rounded-lg p-2 text-xs text-green-600 hover:bg-green-100">
                            <i class="fa-solid fa-check"></i>
                        </button>
                    </form>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('dashboard.contact-messages.destroy', $message)); ?>"
                      onsubmit="return confirm('Delete this message?')">
                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="rounded-lg p-2 text-xs text-red-500 hover:bg-red-100">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="7" class="px-4 py-10 text-center text-gray-400">No contact messages found.</td>
    </tr>
<?php endif; ?>
<?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/dashboard/contact-messages/partials/rows.blade.php ENDPATH**/ ?>