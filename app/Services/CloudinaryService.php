<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Thin wrapper around the Cloudinary SDK used for all image uploads in the
 * app (products, categories, brands, banners, blogs, testimonials, settings,
 * media library). Uploads are auto-compressed (quality:auto, fetch_format:auto)
 * so Cloudinary serves the smallest suitable format (e.g. WebP/AVIF) per request.
 *
 * destroy() also accepts legacy local storage paths (pre-Cloudinary records)
 * and deletes them from the "public" disk instead, so old data keeps working
 * without a backfill migration.
 */
class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(config('cloudinary.url'));
    }

    /**
     * Upload a file to Cloudinary and return its secure delivery URL.
     */
    public function upload(UploadedFile $file, string $folder, string $resourceType = 'image'): string
    {
        $result = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
            'folder' => trim(config('cloudinary.folder'), '/').'/'.trim($folder, '/'),
            'resource_type' => $resourceType,
            'quality' => 'auto',
            'fetch_format' => 'auto',
        ]);

        return $result['secure_url'];
    }

    /**
     * Delete a previously stored image, whether it's a Cloudinary URL or a
     * legacy local "storage/..." relative path.
     */
    public function destroy(?string $path, string $resourceType = 'image'): void
    {
        if (! $path) {
            return;
        }

        if (! str_starts_with($path, 'http')) {
            Storage::disk('public')->delete($path);

            return;
        }

        $publicId = $this->publicIdFromUrl($path);

        if (! $publicId) {
            return;
        }

        try {
            $this->cloudinary->uploadApi()->destroy($publicId, ['resource_type' => $resourceType]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Extract the public_id from a Cloudinary delivery URL, e.g.
     * https://res.cloudinary.com/<cloud>/image/upload/v169.../kareons/products/abc123.webp
     * -> kareons/products/abc123
     */
    protected function publicIdFromUrl(string $url): ?string
    {
        if (! preg_match('#/upload/(?:v\d+/)?([^?]+)\.\w+$#', $url, $matches)) {
            return null;
        }

        return $matches[1];
    }
}
