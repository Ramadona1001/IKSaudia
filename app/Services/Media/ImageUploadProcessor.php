<?php

namespace App\Services\Media;

use App\Contracts\VirusScanner;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class ImageUploadProcessor
{
    public function __construct(
        protected VirusScanner $virusScanner,
    ) {}

    public function processPublicDiskPath(string $relativePath): void
    {
        if (! config('security.uploads.strip_exif', true)) {
            return;
        }

        $disk = Storage::disk('public');

        if (! $disk->exists($relativePath)) {
            return;
        }

        $absolute = $disk->path($relativePath);

        if (! $this->virusScanner->scan($absolute)) {
            $disk->delete($relativePath);

            throw new RuntimeException('Uploaded file failed security scan.');
        }

        $this->stripExifAndReencode($absolute);
    }

    protected function stripExifAndReencode(string $absolutePath): void
    {
        $info = @getimagesize($absolutePath);

        if ($info === false) {
            return;
        }

        $mime = $info['mime'] ?? '';

        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($absolutePath),
            'image/png' => @imagecreatefrompng($absolutePath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($absolutePath) : false,
            'image/gif' => @imagecreatefromgif($absolutePath),
            default => false,
        };

        if ($image === false) {
            return;
        }

        match ($mime) {
            'image/jpeg' => imagejpeg($image, $absolutePath, 88),
            'image/png' => imagepng($image, $absolutePath, 6),
            'image/webp' => function_exists('imagewebp') ? imagewebp($image, $absolutePath, 88) : null,
            'image/gif' => imagegif($image, $absolutePath),
            default => null,
        };

        imagedestroy($image);
    }
}
