<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;

class CloudinaryService
{
    private Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key' => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ]
        ]);
    }

    /**
     * Upload an image to Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder
     * @return string|null The secure URL of the uploaded image
     */
    public function uploadImage(UploadedFile $file, string $folder = 'posts'): ?string
    {
        try {
            $response = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => 'socialpulse/' . $folder,
                    'resource_type' => 'auto',
                    'eager' => ['quality' => 'auto'],
                    'eager_async' => true,
                ]
            );

            return $response['secure_url'] ?? null;
        } catch (\Exception $e) {
            \Log::error('Cloudinary upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete an image from Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function deleteImage(string $publicId): bool
    {
        try {
            $this->cloudinary->uploadApi()->destroy($publicId);
            return true;
        } catch (\Exception $e) {
            \Log::error('Cloudinary delete failed: ' . $e->getMessage());
            return false;
        }
    }
}
