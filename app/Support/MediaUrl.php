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
        foreach (['images/Farmsea.webp', 'images/farmsea.webp', 'images/logo.webp', 'images/logo.png'] as $candidate) {
            if (file_exists(public_path($candidate))) {
                return asset($candidate);
            }
        }

        return asset('images/Farmsea.webp');
    }
}
