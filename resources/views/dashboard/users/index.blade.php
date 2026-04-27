@extends('layouts.dashboard')
@section('title', 'Users')
@section('page_title', 'User Management')

@section('content')
<div class="p-4 lg:p-6">

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @foreach([['Total Users',$stats['total'],'blue-500'],['Active Users',$stats['active'],'green-500'],['Blocked Users',$stats['blocked'],'red-500']] as [$label,$val,$color])
        <div class="bg-white p-5 rounded-2xl shadow flex items-center gap-4">
            <div class="w-12 h-12 bg-{{ $color }} text-white flex items-center justify-center rounded-xl text-xl">
                {{ $label === 'Total Users' ? '👤' : ($label === 'Active Users' ? '✔' : '✖') }}
            </div>
            <div>
                <p class="text-sm text-gray-500">{{ $label }}</p>
                <h2 class="text-2xl font-bold">{{ $val }}</h2>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-6 flex flex-col gap-3 rounded-2xl bg-white p-4 shadow sm:flex-row sm:flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by user ID, name, email, phone..."
               class="w-full rounded-full border px-4 py-2 text-sm outline-none sm:w-auto sm:flex-1">
        <select name="status" class="px-4 py-2 rounded-full border text-sm outline-none">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-full text-sm">Filter</button>
        <a href="{{ route('dashboard.users') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm">Reset</a>
    </form>

    {{-- Table --}}
    <div class="space-y-4 md:hidden">
        @forelse($users as $user)
            <article class="rounded-2xl bg-white p-4 shadow">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-700">#{{ $user->id }}</p>
                        <p class="mt-1 text-base font-bold text-slate-900">{{ $user->name }}</p>
                        <p class="mt-1 break-all text-sm text-gray-600">{{ $user->email }}</p>
                    </div>
                    <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        {{ ucfirst($user->status ?? 'active') }}
                    </span>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Phone</p>
                        <p class="mt-1 text-sm text-gray-700">{{ $user->phone ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">Orders</p>
                        <p class="mt-1 text-sm font-bold text-indigo-600">{{ $user->orders_count }}</p>
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('dashboard.users.show', $user) }}"
                       class="inline-flex w-full items-center justify-center rounded-xl bg-blue-50 px-3 py-2 text-xs font-bold text-blue-600 hover:bg-blue-100 sm:w-auto">
                        View
                    </a>
                    <form method="POST" action="{{ route('dashboard.users.toggle', $user) }}" class="w-full sm:w-auto">
                        @csrf @method('PATCH')
                        <button type="submit"
                                class="w-full rounded-xl px-3 py-2 text-xs font-bold {{ $user->status === 'active' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                            {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-2xl bg-white px-4 py-10 text-center text-gray-400 shadow">No users found.</div>
        @endforelse
    </div>

    <div class="hidden w-full overflow-x-auto rounded-2xl bg-white shadow md:block">
        <table class="w-full text-sm min-w-[700px]">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3">user_id</th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Orders</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 font-semibold text-slate-700">#{{ $user->id }}</td>
                    <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $user->phone ?? '—' }}</td>
                    <td class="px-4 py-3 text-center font-bold text-indigo-600">{{ $user->orders_count }}</td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2 items-center">
                            <a href="{{ route('dashboard.users.show', $user) }}"
                               class="text-blue-600 text-xs font-bold hover:underline">View</a>
                            <form method="POST" action="{{ route('dashboard.users.toggle', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="text-xs font-bold {{ $user->status === 'active' ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                    {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $users->withQueryString()->links() }}</div>
</div>
@endsection
