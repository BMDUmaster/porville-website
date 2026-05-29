<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Handles image upload with automatic conversion to AVIF or WebP.
 *
 * Conversion priority (uses first format the server supports):
 *   1. AVIF  – best compression, modern browsers
 *   2. WebP  – excellent compression, wide support
 *   3. Original format – safe fallback (jpg/png/gif)
 *
 * Override via .env:
 *   IMAGE_FORMAT=avif   → force AVIF
 *   IMAGE_FORMAT=webp   → force WebP
 *   IMAGE_FORMAT=original → skip conversion, keep original
 *   IMAGE_QUALITY=85    → conversion quality (1-100, default 85)
 */
class WebpImage
{
    /**
     * Store an uploaded image, converting to AVIF or WebP when possible.
     *
     * @param  UploadedFile  $file
     * @param  string        $directory  Relative path inside storage/app/public
     * @param  int           $quality    Compression quality (1-100)
     * @return string        Relative path stored (e.g. "categories/abc123.avif")
     */
    public static function store(UploadedFile $file, string $directory, int $quality = 0): string
    {
        $directory = trim($directory, '/');
        $storageDirectory = storage_path('app/public/' . $directory);

        File::ensureDirectoryExists($storageDirectory);

        // Allow quality override from .env
        if ($quality <= 0) {
            $quality = max(1, min(100, (int) env('IMAGE_QUALITY', 85)));
        }

        $format = strtolower(trim(env('IMAGE_FORMAT', 'auto')));

        // Try conversion based on configured/detected format
        if ($format !== 'original') {
            $converted = self::tryConvert($file, $directory, $storageDirectory, $quality, $format);
            if ($converted !== null) {
                return $converted;
            }
        }

        // Fallback: store original file as-is
        return self::storeOriginal($file, $directory, $storageDirectory);
    }

    // -------------------------------------------------------------------------
    // Conversion logic
    // -------------------------------------------------------------------------

    private static function tryConvert(
        UploadedFile $file,
        string $directory,
        string $storageDirectory,
        int $quality,
        string $format
    ): ?string {
        // Determine which formats to attempt, in priority order
        $attempts = match ($format) {
            'avif' => ['avif'],
            'webp' => ['webp'],
            default => ['avif', 'webp'], // auto: try best first
        };

        foreach ($attempts as $targetFormat) {
            $result = self::convertWithGd($file, $directory, $storageDirectory, $quality, $targetFormat);
            if ($result !== null) {
                return $result;
            }
        }

        return null;
    }

    private static function convertWithGd(
        UploadedFile $file,
        string $directory,
        string $storageDirectory,
        int $quality,
        string $targetFormat
    ): ?string {
        // Check GD support for the target format
        if ($targetFormat === 'avif' && ! function_exists('imageavif')) {
            return null;
        }
        if ($targetFormat === 'webp' && ! function_exists('imagewebp')) {
            return null;
        }

        $source = self::createGdImage($file);
        if (! $source) {
            return null;
        }

        try {
            $source = self::fixOrientation($source, $file);

            if (function_exists('imagepalettetotruecolor')) {
                @imagepalettetotruecolor($source);
            }

            @imagealphablending($source, true);
            @imagesavealpha($source, true);

            $filename = Str::random(40) . '.' . $targetFormat;
            $targetPath = $storageDirectory . DIRECTORY_SEPARATOR . $filename;

            $saved = match ($targetFormat) {
                'avif' => @imageavif($source, $targetPath, max(0, min(100, $quality))),
                'webp' => @imagewebp($source, $targetPath, max(1, min(100, $quality))),
                default => false,
            };

            if ($saved && File::isFile($targetPath) && filesize($targetPath) > 0) {
                return $directory . '/' . $filename;
            }

            // Clean up empty/failed file
            if (File::isFile($targetPath)) {
                @unlink($targetPath);
            }
        } catch (\Throwable $e) {
            report($e);
        } finally {
            if ($source instanceof \GdImage || is_resource($source)) {
                @imagedestroy($source);
            }
        }

        return null;
    }

    // -------------------------------------------------------------------------
    // GD helpers
    // -------------------------------------------------------------------------

    /**
     * Create a GD image resource from the uploaded file.
     *
     * @return \GdImage|resource|false
     */
    private static function createGdImage(UploadedFile $file)
    {
        $path = $file->getRealPath();

        if (! $path || ! is_readable($path)) {
            return false;
        }

        return match ($file->getMimeType()) {
            'image/jpeg', 'image/jpg'  => @imagecreatefromjpeg($path),
            'image/png'                => @imagecreatefrompng($path),
            'image/gif'                => @imagecreatefromgif($path),
            'image/webp'               => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            'image/avif'               => function_exists('imagecreatefromavif') ? @imagecreatefromavif($path) : false,
            default                    => false,
        };
    }

    /**
     * Rotate image based on EXIF orientation (JPEG only).
     *
     * @param  \GdImage|resource  $image
     * @return \GdImage|resource
     */
    private static function fixOrientation($image, UploadedFile $file)
    {
        try {
            if (
                ! function_exists('exif_read_data') ||
                ! in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)
            ) {
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

    // -------------------------------------------------------------------------
    // Original-format fallback
    // -------------------------------------------------------------------------

    private static function storeOriginal(UploadedFile $file, string $directory, string $storageDirectory): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: '');
        $extension = preg_replace('/[^a-z0-9]/', '', $extension);

        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
        if (! in_array($extension, $allowed, true)) {
            // Derive extension from MIME type as fallback
            $extension = match ($file->getMimeType()) {
                'image/jpeg', 'image/jpg' => 'jpg',
                'image/png'               => 'png',
                'image/gif'               => 'gif',
                'image/webp'              => 'webp',
                'image/avif'              => 'avif',
                default                   => 'jpg',
            };
        }

        $filename = Str::random(40) . '.' . $extension;
        $targetPath = $storageDirectory . DIRECTORY_SEPARATOR . $filename;

        // Attempt 1: copy()
        $source = $file->getRealPath();
        if ($source && is_readable($source)) {
            if (@copy($source, $targetPath) && File::isFile($targetPath)) {
                return $directory . '/' . $filename;
            }

            // Attempt 2: file_get_contents / file_put_contents
            $contents = @file_get_contents($source);
            if ($contents !== false && @file_put_contents($targetPath, $contents) !== false) {
                return $directory . '/' . $filename;
            }
        }

        // Attempt 3: move()
        try {
            $movedFile = $file->move($storageDirectory, $filename);
            if ($movedFile && File::isFile($targetPath)) {
                return $directory . '/' . $filename;
            }
        } catch (\Throwable) {
            // fall through
        }

        // Attempt 4: Laravel storeAs()
        $storedPath = $file->storeAs($directory, $filename, 'public');
        if ($storedPath) {
            return is_string($storedPath) ? $storedPath : $directory . '/' . $filename;
        }

        throw new \RuntimeException(
            'Image could not be saved. Please run: chmod -R 775 storage && php artisan storage:link'
        );
    }
}
