<?php

namespace App\Support;

class MediaUrl
{
    public static function storage(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    public static function brandLogo(): string
    {
        foreach (['images/porville-logo.webp', 'images/porville-logo.png', 'images/porville-logo.jpg', 'images/logo.webp', 'images/logo.png'] as $candidate) {
            if (file_exists(public_path($candidate))) {
                return asset($candidate);
            }
        }

        return asset('images/porville-logo.jpg');
    }
}
