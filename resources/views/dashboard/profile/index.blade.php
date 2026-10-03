@extends('layouts.dashboard')
@section('title', 'Profile')
@section('page_title', 'Profile Management')

@section('content')
<div class="p-4 lg:p-8">
    <div class="mx-auto w-full max-w-none space-y-6">

        <div class="overflow-hidden rounded-[28px] bg-gradient-to-r from-[#2245b6] via-[#2c67d8] to-[#4288f0] text-white shadow-[0_22px_45px_rgba(37,99,235,0.26)]">
            <div class="flex flex-col gap-6 px-6 py-7 md:px-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-5">
                    <div class="h-20 w-20 overflow-hidden rounded-full border-4 border-white/20 bg-white/15 text-3xl font-black shadow-lg sm:h-24 sm:w-24 sm:text-4xl">
                        <img id="profilePhotoPreview" src="{{ $user->photo_url }}" class="{{ $user->photo_url ? '' : 'hidden' }} h-full w-full object-cover" alt="{{ $user->name }}">
                        <div id="profilePhotoInitial" class="{{ $user->photo_url ? 'hidden' : 'flex' }} h-full w-full items-center justify-center">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.28em] text-white/70">Admin Profile</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight sm:text-3xl">{{ $user->name }}</h1>
                        <p class="mt-1 text-sm font-semibold text-white/85">{{ $user->role === 'sub_admin' ? 'Sub Admin' : 'Main Admin' }}</p>
                        <p class="text-sm text-white/70">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="grid w-full gap-3 sm:grid-cols-3 lg:max-w-xl">
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur-sm">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/60">Phone</p>
                        <p class="mt-2 text-base font-bold text-white">{{ $user->phone ?: 'Not added' }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur-sm">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/60">Status</p>
                        <p class="mt-2 text-base font-bold text-white">{{ ucfirst($user->status ?? 'active') }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur-sm">
                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/60">Account</p>
                        <p class="mt-2 text-base font-bold text-white">Dashboard Admin</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_1fr]">
            <div class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm lg:p-7">
                <div class="mb-6 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-black tracking-tight text-slate-900">Update Profile</h2>
                        <p class="mt-1 text-sm text-slate-400">Manage your admin details and photo from here.</p>
                    </div>
                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-amber-600">Profile</span>
                </div>

                <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data" class="grid gap-5 md:grid-cols-2">
                    @csrf
                    @method('PUT')

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Full Name</label>
                        <input type="text" name="name" value="{{ $user->name }}" required
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Phone</label>
                        <input type="text" name="phone" value="{{ $user->phone ?? '' }}"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Email <span class="font-normal text-slate-400">(login + admin alerts)</span></label>
                        <input type="email" name="email" id="profileEmail" value="{{ old('email', $user->email) }}" required
                               data-original="{{ $user->email }}" oninput="toggleEmailPassword()"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                        @error('email')
                            <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Only needed when the email changes --}}
                    <div id="emailPasswordField" class="md:col-span-2 {{ $errors->has('email_password') || old('email', $user->email) !== $user->email ? '' : 'hidden' }}">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Current Password <span class="font-normal text-slate-400">(required to change the email)</span></label>
                        <input type="password" name="email_password" autocomplete="current-password"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                        <p class="mt-2 text-xs text-slate-400">Use a real inbox you check: you will log in with this email, and new order / new customer emails and password reset codes go here.</p>
                        @error('email_password')
                            <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-bold text-slate-700">Profile Photo</label>
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-5">
                            <input type="file" name="photo" accept="image/*" onchange="previewProfilePhoto(this)" class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-xl file:border-0 file:bg-amber-600 file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-white hover:file:bg-amber-700">
                            <p class="mt-2 text-xs text-slate-400">JPG, PNG or WEBP up to 5 MB. The preview updates as soon as you choose a photo; click Update Profile to save it.</p>
                        </div>
                    </div>

                    <div class="md:col-span-2 pt-1">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-amber-600 px-8 py-3 text-sm font-bold text-white transition hover:bg-amber-700 sm:w-auto">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <div class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm lg:p-7">
                <div class="mb-6 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-2xl font-black tracking-tight text-slate-900">Change Password</h2>
                        <p class="mt-1 text-sm text-slate-400">Keep your admin account secure with a fresh password.</p>
                    </div>
                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-red-500">Security</span>
                </div>

                <form method="POST" action="{{ route('dashboard.profile.password') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Current Password</label>
                        <input type="password" name="current_password" required
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                        @error('current_password')
                            <p class="mt-2 text-xs font-semibold text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">New Password</label>
                        <input type="password" name="password" required minlength="8"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-amber-500 focus:bg-white">
                    </div>

                    <a href="{{ route('dashboard.password.forgot') }}" class="inline-block text-xs font-bold text-amber-700 hover:underline">
                        <i class="fa-solid fa-key mr-1"></i> Forgot your current password? Reset it with an email OTP
                    </a>

                    <div class="pt-1">
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-red-500 px-8 py-3 text-sm font-bold text-white transition hover:bg-red-600 sm:w-auto">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleEmailPassword() {
    const email = document.getElementById('profileEmail');
    const changed = email.value.trim().toLowerCase() !== email.dataset.original.toLowerCase();
    document.getElementById('emailPasswordField').classList.toggle('hidden', !changed);
}

function previewProfilePhoto(input) {
    const file = input.files && input.files[0];
    if (!file) return;

    const preview = document.getElementById('profilePhotoPreview');
    preview.src = URL.createObjectURL(file);
    preview.classList.remove('hidden');
    document.getElementById('profilePhotoInitial').classList.add('hidden');
    document.getElementById('profilePhotoInitial').classList.remove('flex');
}
</script>
@endsection
