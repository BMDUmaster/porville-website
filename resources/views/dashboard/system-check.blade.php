@extends('layouts.dashboard')
@section('title', 'System Check')
@section('page_title', 'Server System Check')

@section('content')
<div class="p-6 max-w-4xl">
    @if($hasErrors)
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <strong>Issues found.</strong> Fix the items marked in red below, then try adding categories/products again.
        </div>
    @else
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            All critical checks passed.
        </div>
    @endif

    <div class="bg-white rounded-xl border divide-y text-sm">
        <div class="p-4 flex justify-between">
            <span>APP_URL</span>
            <span class="font-mono">{{ $checks['app_url'] }}</span>
        </div>
        <div class="p-4 flex justify-between">
            <span>Database</span>
            <span class="{{ $checks['database']['ok'] ? 'text-green-600' : 'text-red-600' }}">{{ $checks['database']['message'] }}</span>
        </div>
        <div class="p-4 flex justify-between">
            <span>storage/app/public writable</span>
            <span class="{{ $checks['storage_writable'] ? 'text-green-600' : 'text-red-600' }}">{{ $checks['storage_writable'] ? 'Yes' : 'No — chmod 775 storage' }}</span>
        </div>
        <div class="p-4 flex justify-between">
            <span>PHP GD extension</span>
            <span class="{{ $checks['gd'] ? 'text-green-600' : 'text-amber-600' }}">{{ $checks['gd'] ? 'Enabled' : 'Disabled (uploads use original format)' }}</span>
        </div>
        <div class="p-4 flex justify-between">
            <span>Logo file (public/images/Farmsea.webp)</span>
            <span class="{{ $checks['logo_file'] ? 'text-green-600' : 'text-red-600' }}">{{ $checks['logo_file'] ? 'Found' : 'Missing' }}</span>
        </div>
        <div class="p-4">
            <p class="font-semibold mb-2">Pending migrations: {{ $checks['migrations']['pending'] }}</p>
            @if($checks['migrations']['pending'] > 0)
                <p class="text-red-600 mb-2">Run on server: <code class="bg-slate-100 px-2 py-1 rounded">php artisan migrate --force</code></p>
            @endif
            <pre class="text-xs bg-slate-50 p-3 rounded-lg overflow-x-auto max-h-48">{{ $checks['migrations']['output'] }}</pre>
        </div>
        @if(!empty($checks['required_columns']['missing']))
        <div class="p-4">
            <p class="font-semibold text-red-600 mb-2">Missing database columns (causes 500 on add product/coupon):</p>
            <pre class="text-xs bg-red-50 p-3 rounded-lg">{{ json_encode($checks['required_columns']['missing'], JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif
    </div>
</div>
@endsection
