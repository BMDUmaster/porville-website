@extends('layouts.dashboard')
@section('title', 'Customers')
@section('page_title', 'Customer Management')

@section('content')
<div class="p-4 lg:p-6">

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @foreach([['Total Customers',$stats['total'],'amber-500'],['Active Customers',$stats['active'],'green-500'],['Blocked Customers',$stats['blocked'],'red-500']] as [$label,$val,$color])
        <div class="flex items-center gap-4 rounded-2xl bg-white p-5 shadow">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-{{ $color }} text-xl font-bold text-white">
                {{ $label === 'Total Customers' ? 'C' : ($label === 'Active Customers' ? 'A' : 'B') }}
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
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by customer ID, name, email, phone..."
               class="w-full rounded-full border px-4 py-2 text-sm outline-none sm:w-auto sm:flex-1">
        <select name="status" class="rounded-full border px-4 py-2 text-sm outline-none">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>Blocked</option>
        </select>
        <button type="submit" class="rounded-full bg-amber-600 px-4 py-2 text-sm text-white">Filter</button>
        <a href="{{ route('dashboard.users') }}" class="rounded-full bg-gray-200 px-4 py-2 text-sm text-gray-700">Reset</a>
    </form>

    {{-- Mobile Cards --}}
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
                        <p class="mt-1 text-sm font-bold text-amber-600">{{ $user->orders_count }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-gray-400">DOB / Gender</p>
                        <p class="mt-1 text-sm text-gray-700">{{ $user->date_of_birth?->format('d M Y') ?: '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $user->gender ? ucfirst(str_replace('_', ' ', $user->gender)) : 'Not set' }}</p>
                    </div>
                </div>

                <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 p-3">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Delivery Charge</p>
                    @if($user->delivery_charge !== null)
                        <p class="mt-1 text-sm font-semibold text-slate-700">Custom Rs{{ number_format($user->delivery_charge, 2) }}</p>
                    @endif
                    <form method="POST" action="{{ route('dashboard.users.delivery-charge', $user) }}" class="mt-3 flex flex-col gap-2">
                        @csrf
                        @method('PATCH')
                        <input type="number" name="delivery_charge" min="0" step="0.01"
                               value="{{ $user->delivery_charge !== null ? number_format($user->delivery_charge, 2, '.', '') : '' }}"
                               placeholder="{{ number_format($globalDeliveryCharge, 2, '.', '') }}"
                               class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm font-medium text-slate-800 outline-none transition focus:border-amber-500">
                        <button type="submit" class="rounded-xl bg-amber-600 px-3 py-2 text-xs font-bold text-white hover:bg-amber-700">
                            Save
                        </button>
                    </form>
                </div>

                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                    <a href="{{ route('dashboard.users.show', $user) }}"
                       class="inline-flex w-full items-center justify-center rounded-xl bg-amber-50 px-3 py-2 text-xs font-bold text-amber-600 hover:bg-amber-100 sm:w-auto">
                        View
                    </a>
                    <form method="POST" action="{{ route('dashboard.users.toggle', $user) }}" class="w-full sm:w-auto">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                                class="w-full rounded-xl px-3 py-2 text-xs font-bold {{ $user->status === 'active' ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                            {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
                        </button>
                    </form>
                </div>
            </article>
        @empty
            <div class="rounded-2xl bg-white px-4 py-10 text-center text-gray-400 shadow">No customers found.</div>
        @endforelse
    </div>

    {{-- Desktop Table --}}
    <div class="hidden w-full overflow-x-auto rounded-2xl bg-white shadow md:block">
        <table class="w-full min-w-[1460px] table-fixed text-sm">
            <colgroup>
                <col style="width: 70px">
                <col style="width: 100px">
                <col style="width: 160px">
                <col style="width: 260px">
                <col style="width: 145px">
                <col style="width: 155px">
                <col style="width: 75px">
                <col style="width: 220px">
                <col style="width: 110px">
                <col style="width: 165px">
            </colgroup>
            <thead class="bg-amber-600 text-white">
                <tr>
                    <th class="whitespace-nowrap px-3 py-3 text-left">Sr. No.</th>
                    <th class="whitespace-nowrap px-3 py-3 text-left">Customer ID</th>
                    <th class="px-3 py-3 text-left">Name</th>
                    <th class="px-3 py-3 text-left">Email</th>
                    <th class="whitespace-nowrap px-3 py-3 text-left">Phone</th>
                    <th class="px-3 py-3 text-left">DOB / Gender</th>
                    <th class="px-3 py-3 text-center">Orders</th>
                    <th class="whitespace-nowrap px-3 py-3 text-left">Delivery Charge</th>
                    <th class="whitespace-nowrap px-3 py-3 text-center">Status</th>
                    <th class="whitespace-nowrap px-3 py-3 text-left">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="align-middle hover:bg-gray-50">
                    <td class="px-3 py-3 font-semibold text-slate-500">{{ $users->firstItem() + $loop->index }}</td>
                    <td class="px-3 py-3 font-semibold text-slate-700">#{{ $user->id }}</td>
                    <td class="truncate px-3 py-3 font-medium" title="{{ $user->name }}">{{ $user->name }}</td>
                    <td class="truncate px-3 py-3 text-gray-600" title="{{ $user->email }}">{{ $user->email }}</td>
                    <td class="whitespace-nowrap px-3 py-3 text-gray-600">{{ $user->phone ?: '-' }}</td>
                    <td class="px-3 py-3 text-gray-600">
                        <p>{{ $user->date_of_birth?->format('d M Y') ?: '-' }}</p>
                        <p class="mt-1 text-xs text-gray-400">{{ $user->gender ? ucfirst(str_replace('_', ' ', $user->gender)) : 'Not set' }}</p>
                    </td>
                    <td class="px-3 py-3 text-center font-bold text-amber-600">{{ $user->orders_count }}</td>
                    <td class="px-3 py-3">
                        <div>
                            @if($user->delivery_charge !== null)
                                <p class="text-xs font-bold text-amber-600">Custom Rs{{ number_format($user->delivery_charge, 2) }}</p>
                            @endif
                            <form method="POST" action="{{ route('dashboard.users.delivery-charge', $user) }}" class="mt-2 flex items-center gap-2 whitespace-nowrap">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="delivery_charge" min="0" step="0.01"
                                       value="{{ $user->delivery_charge !== null ? number_format($user->delivery_charge, 2, '.', '') : '' }}"
                                       placeholder="{{ number_format($globalDeliveryCharge, 2, '.', '') }}"
                                       class="w-24 rounded-lg border border-slate-300 px-2 py-1.5 text-xs font-semibold text-slate-800 outline-none transition focus:border-amber-500">
                                <button type="submit" class="rounded-lg bg-amber-600 px-2.5 py-1.5 text-xs font-bold text-white hover:bg-amber-700">
                                    Save
                                </button>
                            </form>
                        </div>
                    </td>
                    <td class="px-3 py-3 text-center">
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $user->status === 'active' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex items-center gap-2 whitespace-nowrap">
                            <a href="{{ route('dashboard.users.show', $user) }}"
                               class="text-xs font-bold text-amber-600 hover:underline">View</a>
                            <form method="POST" action="{{ route('dashboard.users.toggle', $user) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="text-xs font-bold {{ $user->status === 'active' ? 'text-red-600' : 'text-green-600' }} hover:underline">
                                    {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="px-4 py-8 text-center text-gray-400">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
        <nav class="mt-6 flex items-center justify-center gap-2" aria-label="Customer pagination">
            @if($users->onFirstPage())
                <span class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-400">Previous</span>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Previous</a>
            @endif

            @foreach($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                <a href="{{ $url }}" class="flex h-9 min-w-9 items-center justify-center rounded-lg px-2 text-sm font-bold transition {{ $page === $users->currentPage() ? 'bg-amber-600 text-white shadow-sm' : 'border border-slate-300 text-slate-700 hover:bg-slate-50' }}">{{ $page }}</a>
            @endforeach

            @if($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">Next</a>
            @else
                <span class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-400">Next</span>
            @endif
        </nav>
    @endif
</div>
@endsection
