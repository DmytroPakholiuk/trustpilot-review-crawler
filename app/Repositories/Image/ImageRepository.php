<?php

namespace App\Repositories\Image;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;

class ImageRepository
{
    public function createNewImagesFromUrls(array $urls): void
    {
        $existingImages = $this->getImagesByUrls($urls);
        $newImages = [];

        foreach ($urls as $url) {
            if (!isset($existingImages[$url])) {
                $newImages[] = [
                    'external_url' => $url,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($newImages) Image::insert($newImages);
    }

    public function getImagesByUrls(array $urls): Collection
    {
        return Image::query()->whereIn('external_url', $urls)->get()->keyBy('external_url');
    }
}
