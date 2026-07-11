<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    private mixed $cloudinary = null;

    public function __construct()
    {
        $cloudName = config('services.cloudinary.cloud_name');
        $apiKey    = config('services.cloudinary.api_key');
        $apiSecret = config('services.cloudinary.api_secret');

        // Only initialise Cloudinary when all three credentials are present.
        if ($cloudName && $apiKey && $apiSecret) {
            try {
                $this->cloudinary = new \Cloudinary\Cloudinary([
                    'cloud' => [
                        'cloud_name' => $cloudName,
                        'api_key'    => $apiKey,
                        'api_secret' => $apiSecret,
                    ],
                ]);
            } catch (\Throwable $e) {
                \Log::warning('Cloudinary could not be initialised: ' . $e->getMessage());
            }
        }
    }

    /**
     * Returns true when Cloudinary is available and configured.
     */
    public function isConfigured(): bool
    {
        return $this->cloudinary !== null;
    }

    /**
     * Upload an image to Cloudinary.
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null The secure URL of the uploaded image, or null if unavailable.
     */
    public function uploadImage(UploadedFile $file, string $folder = 'posts'): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $response = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder'        => 'socialpulse/' . $folder,
                    'resource_type' => 'auto',
                    'eager'         => ['quality' => 'auto'],
                    'eager_async'   => true,
                ]
            );

            return $response['secure_url'] ?? null;
        } catch (\Exception $e) {
            \Log::error('Cloudinary upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete an image from Cloudinary.
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            \Log::error('Cloudinary delete failed: ' . $e->getMessage());
            return false;
        }
    }
}
