<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StorageService
{
    private const ALLOWED_MIME  = ['image/jpeg', 'image/png', 'image/webp'];
    private const ALLOWED_EXT   = ['jpg', 'jpeg', 'png', 'webp'];
    private const MAX_SIZE_BYTES = 2 * 1024 * 1024; // 2MB

    /**
     * Validate and store an uploaded image securely.
     * Returns the storage path or throws on validation failure.
     */
    public function storeImage(UploadedFile $file, string $folder): string
    {
        $this->validateImage($file);

        // Generate a UUID-based filename to prevent directory traversal
        $ext      = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid() . '.' . $ext;
        $path     = $file->storeAs($folder, $filename, 'public');

        return $path;
    }

    public function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function validateImage(UploadedFile $file): void
    {
        // Size check
        if ($file->getSize() > self::MAX_SIZE_BYTES) {
            throw new \InvalidArgumentException('Ukuran file maksimal 2MB.');
        }

        // Extension check
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, self::ALLOWED_EXT, true)) {
            throw new \InvalidArgumentException('Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
        }

        // MIME type check (server-side detection, not from browser header)
        $mime = $file->getMimeType();
        if (!in_array($mime, self::ALLOWED_MIME, true)) {
            throw new \InvalidArgumentException('Tipe file tidak valid.');
        }

        // Read first bytes to verify magic numbers (additional protection)
        $handle = fopen($file->getRealPath(), 'rb');
        $bytes  = fread($handle, 12);
        fclose($handle);

        if (!$this->isValidImageMagicBytes($bytes, $ext)) {
            throw new \InvalidArgumentException('File bukan gambar yang valid.');
        }
    }

    private function isValidImageMagicBytes(string $bytes, string $ext): bool
    {
        $hex = bin2hex($bytes);
        return match($ext) {
            'jpg', 'jpeg' => str_starts_with($hex, 'ffd8ff'),
            'png'         => str_starts_with($hex, '89504e47'),
            'webp'        => str_starts_with($hex, '52494646') && str_contains($hex, '57454250'),
            default       => false,
        };
    }

    public function getPublicUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Clean up orphaned files older than given days.
     */
    public function cleanupOrphans(string $folder, int $olderThanDays = 30): int
    {
        $deleted = 0;
        $cutoff  = now()->subDays($olderThanDays)->timestamp;

        $files = Storage::disk('public')->files($folder);
        foreach ($files as $file) {
            $lastModified = Storage::disk('public')->lastModified($file);
            if ($lastModified < $cutoff) {
                Storage::disk('public')->delete($file);
                $deleted++;
            }
        }

        return $deleted;
    }
}
