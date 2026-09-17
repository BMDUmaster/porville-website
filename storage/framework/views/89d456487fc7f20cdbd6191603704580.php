<?php $__env->startSection('title', 'My Profile'); ?>

<?php $__env->startSection('styles'); ?>
<style>
.profile-shell {
    background: #faf7f0;
}
.profile-soft-shadow {
    box-shadow: 0 18px 48px rgba(15, 23, 42, 0.06);
}
@media (max-width: 640px) {
    .profile-shell .profile-card { padding: 14px; border-radius: 20px; }
    .profile-shell .profile-info-row { padding: 12px; }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $memberSince = $stats['member_since'] ?: optional($user->created_at)->format('d M Y');
    $profileCompletion = max(12, min(100, $stats['profile_completion']));
?>

<div class="profile-shell min-h-screen">
    <div class="mx-auto w-full max-w-[1100px] px-3 py-4 sm:px-4 sm:py-6 md:px-6 md:py-8">
        <div class="overflow-hidden rounded-[30px] border border-amber-500/20 bg-black text-white profile-soft-shadow">
            <div class="flex flex-col gap-6 px-5 py-6 md:px-8 md:py-7 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex items-start gap-4">
                    <div class="relative flex h-20 w-20 items-center justify-center rounded-[24px] border border-amber-500/40 bg-amber-500/10 text-3xl font-black text-amber-400">
                        <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                        <span class="absolute -bottom-1 -right-1 flex h-6 w-6 items-center justify-center rounded-full border-2 border-black bg-amber-400 text-[11px] text-black">
                            <i class="fa-solid fa-check"></i>
                        </span>
                    </div>
                    <div class="pt-1">
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-amber-400">Porville Account</p>
                        <h1 class="font-classic mt-1 text-2xl font-bold md:text-[30px]"><?php echo e($user->name); ?></h1>
                        <p class="mt-1 text-sm text-stone-300">
                            <?php echo e(in_array($user->status, ['blocked', 'inactive'], true) ? 'Account Restricted' : 'Verified Customer'); ?>

                        </p>
                        <div class="mt-3 flex flex-wrap gap-2 text-[11px] font-semibold">
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5">
                                <i class="fa-solid fa-envelope text-[10px] text-amber-400"></i>
                                <?php echo e($user->email); ?>

                            </span>
                            <?php if($user->phone): ?>
                                <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5">
                                    <i class="fa-solid fa-phone text-[10px] text-amber-400"></i>
                                    <?php echo e($user->phone); ?>

                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 lg:justify-end">
                    <a href="#profile-edit" class="inline-flex items-center gap-2 rounded-full bg-amber-500 px-4 py-2 text-xs font-bold text-black shadow-sm transition hover:bg-amber-400">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Edit Profile
                    </a>
                    <a href="#security-panel" class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 text-xs font-bold text-white transition hover:bg-white/10">
                        <i class="fa-solid fa-lock"></i>
                        Password
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-[18px] border border-amber-100 bg-white px-4 py-4 text-center profile-soft-shadow">
                <p class="font-classic text-xl font-bold text-amber-700 md:text-2xl"><?php echo e($stats['total_orders']); ?></p>
                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Orders</p>
            </div>
            <div class="rounded-[18px] border border-amber-100 bg-white px-4 py-4 text-center profile-soft-shadow">
                <p class="font-classic text-xl font-bold text-amber-700 md:text-2xl">&#8377;<?php echo e(number_format($stats['total_spent'], 0)); ?></p>
                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Total Spent</p>
            </div>
            <div class="rounded-[18px] border border-amber-100 bg-white px-4 py-4 text-center profile-soft-shadow">
                <p class="font-classic text-xl font-bold text-amber-700 md:text-2xl"><?php echo e($memberSince); ?></p>
                <p class="mt-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-400">Member Since</p>
            </div>
        </div>

        <div class="mt-5 grid min-w-0 gap-5 lg:grid-cols-2">
            <div class="min-w-0 space-y-5">
                <div class="profile-card min-w-0 overflow-hidden rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <div class="mb-4 min-w-0">
                        <h2 class="text-sm font-extrabold text-slate-900">
                            <i class="fa-solid fa-circle-user mr-2 text-amber-600"></i>
                            Personal Information
                        </h2>
                        <p class="mt-1 text-xs text-slate-400">Your main account details in one place.</p>
                    </div>

                    <div class="space-y-3">
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Full Name</p>
                            <p class="mt-1 text-sm font-semibold text-slate-900"><?php echo e($user->name); ?></p>
                        </div>
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Email Address</p>
                            <div class="mt-1 flex min-w-0 items-center justify-between gap-2">
                                <p class="min-w-0 break-all text-sm font-semibold text-slate-900"><?php echo e($user->email); ?></p>
                                <span class="flex-shrink-0 rounded-full bg-amber-50 px-2 py-1 text-[9px] font-bold uppercase tracking-[0.12em] text-amber-700 sm:px-2.5 sm:text-[10px] sm:tracking-[0.18em]">
                                    <?php echo e($user->email_verified_at ? 'Verified' : 'Primary'); ?>

                                </span>
                            </div>
                        </div>
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Phone Number</p>
                            <div class="mt-1 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900"><?php echo e($user->phone ?: 'Add your phone number'); ?></p>
                                <i class="fa-solid fa-phone text-xs text-slate-400"></i>
                            </div>
                        </div>
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Date of Birth</p>
                            <div class="mt-1 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900"><?php echo e($user->date_of_birth?->format('d M Y') ?: 'Add this later'); ?></p>
                                <i class="fa-solid fa-cake-candles text-xs text-slate-400"></i>
                            </div>
                        </div>
                        <div class="profile-info-row min-w-0 rounded-2xl border border-slate-200 bg-slate-50/70 px-4 py-3">
                            <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-400">Gender</p>
                            <div class="mt-1 flex items-center justify-between gap-3">
                                <p class="text-sm font-semibold text-slate-900"><?php echo e($user->gender ? ucfirst(str_replace('_', ' ', $user->gender)) : 'Not set'); ?></p>
                                <i class="fa-solid fa-user text-xs text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-sm font-extrabold text-slate-900">
                                <i class="fa-solid fa-location-dot mr-2 text-amber-600"></i>
                                Delivery Address
                            </h2>
                            <p class="mt-1 text-xs text-slate-400">Latest delivery location linked to your orders.</p>
                        </div>
                        <a href="<?php echo e(route('frontend.orders')); ?>" class="text-[11px] font-bold uppercase tracking-[0.18em] text-amber-700">View Orders</a>
                    </div>

                    <div class="rounded-[20px] border border-amber-100 bg-amber-50/60 px-4 py-4">
                        <?php if(!empty($latestAddress)): ?>
                            <div class="grid gap-2 text-sm text-slate-700">
                                <?php if(!empty($latestAddress['name'])): ?>
                                    <p class="font-bold text-slate-900"><?php echo e($latestAddress['name']); ?></p>
                                <?php endif; ?>
                                <?php if(!empty($latestAddress['address'])): ?>
                                    <p><i class="fa-solid fa-road mr-2 text-amber-600"></i><?php echo e($latestAddress['address']); ?></p>
                                <?php endif; ?>
                                <p>
                                    <i class="fa-solid fa-city mr-2 text-amber-600"></i>
                                    <?php echo e(collect([$latestAddress['city'] ?? null, $latestAddress['state'] ?? null, $latestAddress['pincode'] ?? null])->filter()->implode(', ') ?: 'Address details available in latest order'); ?>

                                </p>
                                <?php if(!empty($latestAddress['phone'])): ?>
                                    <p><i class="fa-solid fa-phone mr-2 text-amber-600"></i><?php echo e($latestAddress['phone']); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="mt-4 inline-flex rounded-full bg-white px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-amber-700 shadow-sm">
                                Primary Address
                            </div>
                        <?php else: ?>
                            <div class="rounded-2xl border border-dashed border-amber-200 bg-white/70 px-4 py-5 text-sm text-slate-500">
                                No delivery address yet. Place your first order and your saved shipping details will appear here.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div id="security-panel" class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-shield-heart mr-2 text-red-500"></i>
                        Security
                    </h2>
                    <div class="mt-4 space-y-3 text-sm">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700">
                            <span><i class="fa-solid fa-user-check mr-2 text-slate-400"></i>Account State</span>
                            <span class="text-[11px] font-bold uppercase tracking-[0.18em] <?php echo e(in_array($user->status, ['blocked', 'inactive'], true) ? 'text-red-500' : 'text-emerald-600'); ?>">
                                <?php echo e(in_array($user->status, ['blocked', 'inactive'], true) ? 'Restricted' : 'Active'); ?>

                            </span>
                        </div>
                        <a href="<?php echo e(route('frontend.orders')); ?>" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 transition hover:border-amber-200 hover:bg-amber-50/30">
                            <span><i class="fa-solid fa-box mr-2 text-slate-400"></i>Order Activity</span>
                            <i class="fa-solid fa-angle-right text-xs text-slate-400"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="min-w-0 space-y-5">
                <div id="profile-edit" class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-user-pen mr-2 text-amber-600"></i>
                        Edit Profile
                    </h2>
                    <form method="POST" action="<?php echo e(route('frontend.profile.update')); ?>" class="mt-4 space-y-3">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Full Name</label>
                            <input type="text" name="name" value="<?php echo e(old('name', $user->name)); ?>" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Phone</label>
                            <input type="tel" id="profilePhoneInput" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>"
                                   inputmode="numeric" pattern="(?:\d{10}|\d{12})" minlength="10" maxlength="12" autocomplete="off"
                                   title="Phone number must be 10 or 12 digits"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                            <p class="mt-1 text-[11px] font-semibold text-slate-400">Only 10 or 12 digit numbers are allowed.</p>
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="<?php echo e(old('date_of_birth', $user->date_of_birth?->format('Y-m-d'))); ?>" max="<?php echo e(now()->subDay()->format('Y-m-d')); ?>"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Gender</label>
                            <select name="gender" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                                <option value="">Select Gender</option>
                                <?php $__currentLoopData = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other', 'prefer_not_to_say' => 'Prefer not to say']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php if(old('gender', $user->gender) === $value): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Email</label>
                            <input type="email" value="<?php echo e($user->email); ?>" disabled class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm text-slate-400">
                        </div>
                        <button type="submit" class="w-full rounded-2xl border border-amber-500 bg-black px-4 py-3 text-sm font-bold text-white transition hover:bg-neutral-900">
                            Save Profile
                        </button>
                    </form>
                </div>

                <div class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-chart-line mr-2 text-amber-600"></i>
                        Account Snapshot
                    </h2>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-sm font-semibold text-slate-600">Delivered Orders</span>
                            <span class="text-sm font-black text-slate-900"><?php echo e($stats['delivered_orders']); ?></span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-sm font-semibold text-slate-600">Active Orders</span>
                            <span class="text-sm font-black text-slate-900"><?php echo e($stats['active_orders']); ?></span>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-sm font-semibold text-slate-600">Profile Completion</span>
                            <span class="text-sm font-black text-slate-900"><?php echo e($profileCompletion); ?>%</span>
                        </div>
                    </div>
                </div>

                <div id="password-form" class="rounded-[24px] border border-slate-200/80 bg-white p-4 profile-soft-shadow md:p-5">
                    <h2 class="text-sm font-extrabold text-slate-900">
                        <i class="fa-solid fa-lock mr-2 text-amber-600"></i>
                        Update Password
                    </h2>
                    <form method="POST" action="<?php echo e(route('frontend.profile.password')); ?>" class="mt-4 space-y-3">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Current Password</label>
                            <input type="password" name="current_password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">New Password</label>
                            <input type="password" name="password" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Confirm Password</label>
                            <input type="password" name="password_confirmation" required class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                        </div>
                        <button type="submit" class="w-full rounded-2xl border border-amber-500 bg-black px-4 py-3 text-sm font-bold text-white transition hover:bg-neutral-900">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
const profilePhoneInput = document.getElementById('profilePhoneInput');

function sanitizeProfilePhone() {
    if (!profilePhoneInput) {
        return;
    }

    profilePhoneInput.value = profilePhoneInput.value.replace(/\D/g, '').slice(0, 12);
}

profilePhoneInput?.addEventListener('input', sanitizeProfilePhone);
sanitizeProfilePhone();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/frontend/profile.blade.php ENDPATH**/ ?>