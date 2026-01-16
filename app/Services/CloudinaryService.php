<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    public static function upload($file, $folder)
    {
        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('services.cloudinary.cloud_name'),
                'api_key'    => config('services.cloudinary.api_key'),
                'api_secret' => config('services.cloudinary.api_secret'),
            ],
        ]);

        $result = $cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            [
                'folder' => $folder,
                'resource_type' => 'image'
            ]
        );

        return $result['secure_url'];
    }
}
