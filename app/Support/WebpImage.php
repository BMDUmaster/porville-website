<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class WebpImage
{
    public static function store(UploadedFile $file, string $directory, int $quality = 85): string
    {
        $directory = trim($directory, '/');
        $storageDirectory = storage_path('app/public/' . $directory);

        File::ensureDirectoryExists($storageDirectory);

        $relativePath = self::convertWithGd($file, $directory, $storageDirectory, $quality)
            ?? self::convertWithImageMagick($file, $directory, $storageDirectory, $quality);

        if ($relativePath) {
            return $relativePath;
        }

        return self::storeOriginal($file, $directory);
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

        $source = self::applyExifOrientation($source, $file);
        imagepalettetotruecolor($source);
        imagealphablending($source, true);
        imagesavealpha($source, true);

        $filename = Str::random(40) . '.webp';
        $targetPath = $storageDirectory . DIRECTORY_SEPARATOR . $filename;
        $saved = imagewebp($source, $targetPath, max(1, min(100, $quality)));
        imagedestroy($source);

        if (! $saved || ! File::exists($targetPath)) {
            return null;
        }

        return $directory . '/' . $filename;
    }

    private static function createGdImage(UploadedFile $file)
    {
        $path = $file->getRealPath();

        if (! $path) {
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
     * @param resource $image
     * @return resource
     */
    private static function applyExifOrientation($image, UploadedFile $file)
    {
        if (! function_exists('exif_read_data') || ! in_array($file->getMimeType(), ['image/jpeg', 'image/jpg'], true)) {
            return $image;
        }

        $exif = @exif_read_data($file->getRealPath());

        if (! is_array($exif) || empty($exif['Orientation'])) {
            return $image;
        }

        return match ((int) $exif['Orientation']) {
            3 => imagerotate($image, 180, 0) ?: $image,
            6 => imagerotate($image, -90, 0) ?: $image,
            8 => imagerotate($image, 90, 0) ?: $image,
            default => $image,
        };
    }

    private static function convertWithImageMagick(UploadedFile $file, string $directory, string $storageDirectory, int $quality): ?string
    {
        if (! function_exists('exec')) {
            return null;
        }

        try {
            $binary = self::resolveBinary();
        } catch (RuntimeException) {
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

        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || ! File::exists($targetPath)) {
            return null;
        }

        return $directory . '/' . $filename;
    }

    private static function storeOriginal(UploadedFile $file, string $directory): string
    {
        $storedPath = $file->store($directory, 'public');

        if (! $storedPath) {
            throw new RuntimeException('Unable to store uploaded image.');
        }

        return $storedPath;
    }

    private static function resolveBinary(): string
    {
        $configuredPath = env('IMAGEMAGICK_BINARY');

        if ($configuredPath && File::exists($configuredPath)) {
            return $configuredPath;
        }

        $candidates = array_filter([
            ...glob('C:\\Program Files\\ImageMagick-*\\magick.exe') ?: [],
            ...glob('C:\\Program Files (x86)\\ImageMagick-*\\magick.exe') ?: [],
            '/usr/bin/magick',
            '/usr/local/bin/magick',
            '/usr/bin/convert',
            '/usr/local/bin/convert',
            'magick',
            'convert',
        ]);

        foreach ($candidates as $candidate) {
            if (in_array($candidate, ['magick', 'convert'], true)) {
                return $candidate;
            }

            if (File::exists($candidate)) {
                return $candidate;
            }
        }

        throw new RuntimeException('ImageMagick binary not found for WebP conversion.');
    }
}
