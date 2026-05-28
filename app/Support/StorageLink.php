<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

class StorageLink
{
    public static function ensure(): void
    {
        $publicStorage = public_path('storage');
        $target = storage_path('app/public');

        if (! File::isDirectory($target)) {
            File::ensureDirectoryExists($target);
        }

        if (File::exists($publicStorage)) {
            return;
        }

        if (! function_exists('symlink')) {
            return;
        }

        try {
            @symlink($target, $publicStorage);
        } catch (\Throwable) {
            // Shared hosting may block symlinks; /storage route serves files instead.
        }
    }
}
