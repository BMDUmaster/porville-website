<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WebpImage
{
    /**
     * Save uploaded image. By default keeps original JPG/PNG (best for shared hosting).
     * Set IMAGE_USE_WEBP=true in .env only if server has GD with WebP support.
     */
    public static function store(UploadedFile $file, string $directory, int $quality = 85): string
    {
        $directory = trim($directory, '/');
        $storageDirectory = storage_path('app/public/' . $directory);

        File::ensureDirectoryExists($storageDirectory);

        if (self::shouldConvertToWebp()) {
            try {
                $converted = self::tryConvertToWebp($file, $directory, $storageDirectory, $quality);

                if ($converted) {
                    return $converted;
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        return self::storeOriginal($file, $directory, $storageDirectory);
    }

    private static function shouldConvertToWebp(): bool
    {
        return filter_var(env('IMAGE_USE_WEBP', false), FILTER_VALIDATE_BOOL);
    }

    private static function tryConvertToWebp(UploadedFile $file, string $directory, string $storageDirectory, int $quality): ?string
    {
        return self::convertWithGd($file, $directory, $storageDirectory, $quality)
            ?? self::convertWithImageMagick($file, $directory, $storageDirectory, $quality);
    }

    private static function convertWithGd(UploadedFile $file, string $directory, string $storageDirectory, int $quality): ?string
    {
        if (! function_exists('imagewebp')) {
            return null;
        }

        $source = self::createGdImage($file);

        if (! $source) {
            return null;
        }

        try {
            $source = self::applyExifOrientation($source, $file);

            if (function_exists('imagepalettetotruecolor')) {
                @imagepalettetotruecolor($source);
            }

            @imagealphablending($source, true);
            @imagesavealpha($source, true);

            $filename = Str::random(40) . '.webp';
            $targetPath = $storageDirectory . DIRECTORY_SEPARATOR . $filename;
            $saved = @imagewebp($source, $targetPath, max(1, min(100, $quality)));

            if ($saved && File::isFile($targetPath)) {
                return $directory . '/' . $filename;
            }
        } finally {
            if (is_resource($source) || $source instanceof \GdImage) {
                @imagedestroy($source);
            }
        }

        return null;
    }

    private static function createGdImage(UploadedFile $file)
    {
        $path = $file->getRealPath();

        if (! $path || ! is_readable($path)) {
            return false;
        }

        return match ($file->getMimeType()) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/gif' => @imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    /**
     * @param resource|\GdImage $image
     * @return resource|\GdImage
     */
    private static function applyExifOrientation($image, UploadedFile $file)
    {
        try {
            if (! function_exists('exif_read_data') || ! in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)) {
                return $image;
            }

            $exif = @exif_read_data($file->getRealPath());

            if (! is_array($exif) || empty($exif['Orientation'])) {
                return $image;
            }

            $rotated = match ((int) $exif['Orientation']) {
                3 => @imagerotate($image, 180, 0),
                6 => @imagerotate($image, -90, 0),
                8 => @imagerotate($image, 90, 0),
                default => false,
            };

            return $rotated ?: $image;
        } catch (\Throwable) {
            return $image;
        }
    }

    private static function convertWithImageMagick(UploadedFile $file, string $directory, string $storageDirectory, int $quality): ?string
    {
        if (! function_exists('exec')) {
            return null;
        }

        $binary = self::resolveBinary();

        if (! $binary) {
            return null;
        }

        $filename = Str::random(40) . '.webp';
        $targetPath = $storageDirectory . DIRECTORY_SEPARATOR . $filename;

        $command = sprintf(
            '%s %s -auto-orient -strip -quality %d %s 2>&1',
            escapeshellarg($binary),
            escapeshellarg($file->getRealPath()),
            $quality,
            escapeshellarg($targetPath)
        );

        @exec($command, $output, $exitCode);

        if ($exitCode === 0 && File::isFile($targetPath)) {
            return $directory . '/' . $filename;
        }

        return null;
    }

    private static function storeOriginal(UploadedFile $file, string $directory, string $storageDirectory): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $extension = preg_replace('/[^a-z0-9]/', '', $extension) ?: 'jpg';

        if (! in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $extension = 'jpg';
        }

        $filename = Str::random(40) . '.' . $extension;
        $targetPath = $storageDirectory . DIRECTORY_SEPARATOR . $filename;
        $source = $file->getRealPath();

        if ($source && is_readable($source)) {
            if (@copy($source, $targetPath) && File::isFile($targetPath)) {
                return $directory . '/' . $filename;
            }

            $contents = @file_get_contents($source);

            if ($contents !== false && @file_put_contents($targetPath, $contents) !== false) {
                return $directory . '/' . $filename;
            }
        }

        if (@$file->move($storageDirectory, $filename) && File::isFile($targetPath)) {
            return $directory . '/' . $filename;
        }

        $storedPath = $file->storeAs($directory, $filename, 'public');

        if ($storedPath) {
            return $storedPath;
        }

        throw new \RuntimeException(
            'Image save failed. Run: chmod -R 775 storage && php artisan storage:link'
        );
    }

    private static function resolveBinary(): ?string
    {
        $configuredPath = env('IMAGEMAGICK_BINARY');

        if ($configuredPath && File::exists($configuredPath)) {
            return $configuredPath;
        }

        foreach (['/usr/bin/magick', '/usr/local/bin/magick', '/usr/bin/convert'] as $candidate) {
            if (File::exists($candidate)) {
                return $candidate;
            }
        }

        return null;
    }
}
