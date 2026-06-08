<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageOptimizer
{
    private const MAX_WIDTH = 1200;

    private const JPEG_QUALITY = 82;

    public function storeOptimized(UploadedFile $file, string $directory = 'products'): string
    {
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = Str::uuid() . '.' . ($extension === 'png' ? 'png' : 'jpg');
        $relativePath = trim($directory, '/') . '/' . $filename;
        $absolutePath = Storage::disk('public')->path($relativePath);

        Storage::disk('public')->makeDirectory($directory);

        $this->optimizeToPath($file->getRealPath(), $absolutePath, $extension);

        return 'storage/' . $relativePath;
    }

    private function optimizeToPath(string $sourcePath, string $targetPath, string $extension): void
    {
        if (!function_exists('imagecreatefromstring')) {
            copy($sourcePath, $targetPath);

            return;
        }

        $contents = file_get_contents($sourcePath);
        if ($contents === false) {
            throw new \RuntimeException('Pildi lugemine ebaõnnestus.');
        }

        $image = @imagecreatefromstring($contents);
        if ($image === false) {
            throw new \RuntimeException('Pildi töötlemine ebaõnnestus.');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        if ($width > self::MAX_WIDTH) {
            $newWidth = self::MAX_WIDTH;
            $newHeight = (int) round($height * ($newWidth / $width));
            $resized = imagecreatetruecolor($newWidth, $newHeight);

            if ($extension === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
            $width = $newWidth;
            $height = $newHeight;
        }

        if ($extension === 'png') {
            imagepng($image, $targetPath, 6);
        } else {
            imagejpeg($image, $targetPath, self::JPEG_QUALITY);
        }

        imagedestroy($image);
    }
}
