<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected static function cloudinary(): Cloudinary
    {
        return new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
        ]);
    }

    public static function upload($file, string $folder): string
    {
        $cloudinary = self::cloudinary();

        $result = $cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder,
            ]
        );

        return $result['secure_url'];
    }
}
