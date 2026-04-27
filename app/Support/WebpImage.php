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

        $filename = Str::random(40) . '.webp';
        $targetPath = $storageDirectory . DIRECTORY_SEPARATOR . $filename;
        $binary = self::resolveBinary();

        $command = sprintf(
            '%s %s -auto-orient -strip -quality %d %s 2>&1',
            escapeshellarg($binary),
            escapeshellarg($file->getRealPath()),
            $quality,
            escapeshellarg($targetPath)
        );

        exec($command, $output, $exitCode);

        if ($exitCode !== 0 || ! File::exists($targetPath)) {
            throw new RuntimeException('Unable to convert image to WebP: ' . implode(PHP_EOL, $output));
        }

        return $directory . '/' . $filename;
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
            'magick',
        ]);

        foreach ($candidates as $candidate) {
            if ($candidate === 'magick' || File::exists($candidate)) {
                return $candidate;
            }
        }

        throw new RuntimeException('ImageMagick binary not found for WebP conversion.');
    }
}
