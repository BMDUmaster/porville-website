@extends('layouts.dashboard')
@section('title', 'Admin Management')
@section('page_title', 'Admin Management')

@section('content')
@php
    $formAction = $editing ? route('dashboard.admins.update', $editing) : route('dashboard.admins.store');
    $checked = collect(old('permissions', $editing?->permission_list ?? []));
@endphp
<div class="mx-auto max-w-6xl space-y-5 p-4 md:p-6">

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h2 class="text-xl font-black text-slate-900">Admin Management</h2>
        <p class="mt-1 text-sm text-slate-500">Add sub admins and choose which modules they can open. A sub admin only sees and works on the modules you tick here (tick Dashboard to show them the dashboard). Their own profile is always available.</p>
    </div>

    {{-- Add / edit sub admin --}}
    <form method="POST" action="{{ $formAction }}" class="rounded-2xl border {{ $editing ? 'border-amber-300' : 'border-slate-200' }} bg-white p-5 shadow-sm">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid {{ $editing ? 'fa-user-pen' : 'fa-user-plus' }}"></i></span>
                <div>
                    <h3 class="text-lg font-black text-slate-900">{{ $editing ? 'Edit Sub Admin' : 'Add Sub Admin' }}</h3>
                    <p class="text-xs text-slate-500">{{ $editing ? 'Leave password empty to keep the current one.' : 'They log in at the admin login page with this email and password.' }}</p>
                </div>
            </div>
            @if($editing)
                <a href="{{ route('dashboard.admins') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800">Cancel edit</a>
            @endif
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Name</label>
                <input type="text" name="name" required maxlength="100" value="{{ old('name', $editing->name ?? '') }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Email (login)</label>
                <input type="email" name="email" required value="{{ old('email', $editing->email ?? '') }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Phone <span class="font-normal text-slate-400">(optional)</span></label>
                <input type="text" name="phone" maxlength="20" value="{{ old('phone', $editing->phone ?? '') }}" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Status</label>
                <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-amber-500">
                    <option value="active" @selected(old('status', $editing->status ?? 'active') === 'active')>Active (can log in)</option>
                    <option value="blocked" @selected(old('status', $editing->status ?? 'active') === 'blocked')>Disabled (cannot log in)</option>
                </select>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Password {{ $editing ? '(optional)' : '' }}</label>
                <input type="password" name="password" {{ $editing ? '' : 'required' }} minlength="8" autocomplete="new-password" placeholder="At least 8 characters" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Confirm Password</label>
                <input type="password" name="password_confirmation" {{ $editing ? '' : 'required' }} minlength="8" autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-amber-500">
            </div>
        </div>

        <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-600">Modules this sub admin can use</label>
                <div class="flex gap-3 text-xs font-bold">
                    <button type="button" onclick="setAllModules(true)" class="text-amber-700 hover:underline">Select all</button>
                    <button type="button" onclick="setAllModules(false)" class="text-slate-500 hover:underline">Clear</button>
                </div>
            </div>
            <div class="mt-3 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($modules as $key => $module)
                    <label class="flex cursor-pointer items-center gap-2.5 rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-sm font-semibold text-slate-700 hover:border-amber-300 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                        <input type="checkbox" name="permissions[]" value="{{ $key }}" class="module-check h-4 w-4 accent-amber-600" @checked($checked->contains($key))>
                        <i class="fa-solid {{ $module['icon'] }} w-4 text-amber-600"></i>
                        {{ $module['label'] }}
                    </label>
                @endforeach
            </div>
        </div>

        <div class="mt-5 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-black px-6 py-3 text-sm font-bold text-white hover:bg-neutral-800">
                <i class="fa-solid {{ $editing ? 'fa-floppy-disk' : 'fa-plus' }}"></i> {{ $editing ? 'Update Sub Admin' : 'Create Sub Admin' }}
            </button>
        </div>
    </form>

    {{-- Staff list --}}
    <section class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-[760px] text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Modules</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($staff as $member)
                    <tr class="{{ $editing && $editing->id === $member->id ? 'bg-amber-50' : 'hover:bg-slate-50' }}">
                        <td class="px-4 py-3">
                            <p class="font-bold text-slate-800">{{ $member->name }} @if($member->id === auth()->id())<span class="text-xs font-semibold text-slate-400">(you)</span>@endif</p>
                            <p class="text-xs text-slate-500">{{ $member->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($member->role === 'admin')
                                <span class="rounded-full bg-black px-2.5 py-1 text-[11px] font-bold text-amber-300">Main Admin</span>
                            @else
                                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-800">Sub Admin</span>
                            @endif
                        </td>
                        <td class="max-w-sm px-4 py-3 text-xs text-slate-600">
                            @if($member->role === 'admin')
                                All modules
                            @elseif(count($member->permission_list))
                                {{ collect($member->permission_list)->map(fn ($key) => $modules[$key]['label'] ?? $key)->join(', ') }}
                            @else
                                <span class="text-red-500">No modules yet</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2.5 py-1 text-[11px] font-bold {{ $member->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $member->status === 'active' ? 'Active' : 'Disabled' }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if($member->role === 'sub_admin')
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('dashboard.admins', ['edit' => $member->id]) }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-amber-600 hover:bg-amber-100" title="Edit"><i class="fa-regular fa-pen-to-square"></i></a>
                                    <form method="POST" action="{{ route('dashboard.admins.destroy', $member) }}" onsubmit="return confirm('Remove this sub admin?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-500 hover:bg-red-100" title="Delete"><i class="fa-regular fa-trash-can"></i></button>
                                    </form>
                                </div>
                            @else
                                <p class="text-center text-xs text-slate-400">—</p>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</div>
@endsection

@section('scripts')
<script>
function setAllModules(checked) {
    document.querySelectorAll('.module-check').forEach((box) => { box.checked = checked; });
}
</script>
@endsection
