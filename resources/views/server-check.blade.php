<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porville Server Check</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 720px; margin: 2rem auto; padding: 0 1rem; background: #f8fafc; color: #0f172a; }
        h1 { color: #15803d; }
        .row { display: flex; justify-content: space-between; padding: 0.6rem 0; border-bottom: 1px solid #e2e8f0; }
        .ok { color: #15803d; font-weight: 600; }
        .bad { color: #b91c1c; font-weight: 600; }
        .box { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem 1.25rem; margin-top: 1rem; }
        .note { margin-top: 1.5rem; font-size: 0.9rem; color: #475569; }
        code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Porville Server Check</h1>
    <p>Laravel route — works even when <code>server-check.php</code> file gives 404.</p>

    <div class="box">
        @php
            $yes = fn ($v) => $v ? 'ok' : 'bad';
            $label = fn ($v) => $v ? 'YES' : 'NO';
        @endphp

        <div class="row"><span>PHP version</span><span>{{ $checks['php_version'] }}</span></div>
        <div class="row"><span>GD extension</span><span class="{{ $yes($checks['gd']) }}">{{ $label($checks['gd']) }}</span></div>
        <div class="row"><span>PDO MySQL</span><span class="{{ $yes($checks['pdo_mysql']) }}">{{ $label($checks['pdo_mysql']) }}</span></div>
        <div class="row"><span>.env file</span><span class="{{ $yes($checks['env_file']) }}">{{ $checks['env_file'] ? 'FOUND' : 'MISSING' }}</span></div>
        <div class="row"><span>vendor/ (composer)</span><span class="{{ $yes($checks['vendor']) }}">{{ $checks['vendor'] ? 'FOUND' : 'MISSING' }}</span></div>
        <div class="row"><span>storage writable</span><span class="{{ $yes($checks['storage_writable']) }}">{{ $label($checks['storage_writable']) }}</span></div>
        <div class="row"><span>bootstrap/cache writable</span><span class="{{ $yes($checks['bootstrap_cache_writable']) }}">{{ $label($checks['bootstrap_cache_writable']) }}</span></div>
        <div class="row"><span>public/storage link</span><span class="{{ $yes($checks['public_storage_link']) }}">{{ $label($checks['public_storage_link']) }}</span></div>
        <div class="row"><span>Logo image</span><span class="{{ $yes($checks['logo']) }}">{{ $label($checks['logo']) }}</span></div>
        <div class="row"><span>APP_URL (.env)</span><span>{{ $checks['app_url_env'] ?? '—' }}</span></div>
        <div class="row"><span>DB_DATABASE (.env)</span><span>{{ $checks['db_database_env'] ?? '—' }}</span></div>
        <div class="row"><span>Database connection</span>
            <span class="{{ $yes($checks['database']['ok']) }}">{{ $checks['database']['message'] }}</span>
        </div>
    </div>

    <p class="note">
        Admin login ke baad full check: <code>/porville/dashboard/system-check</code><br>
        Setup command (SSH): <code>php artisan porville:setup</code>
    </p>
</body>
</html>
