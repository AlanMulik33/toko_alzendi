<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    public static function upload($file, $folder)
    {
        $cloudinary = new Cloudinary([
            'cloudinary' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
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
