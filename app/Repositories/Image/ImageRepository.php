<?php

namespace App\Repositories\Image;

use App\Models\Image;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

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

    public function operateNewFilesInBatches(callable $callback): void
    {
        Image::query()->whereNull("path")->chunk(500, function ($images) use ($callback) {
            $callback($images);
        });
    }

    public function operateMissingFilesInBatches(callable $callback): void
    {
        Image::query()->whereNotNull("path")->chunk(500, function ($images) use ($callback) {
            $missing = [];
            foreach ($images as $image) {
                if (!Storage::disk('local')->exists($image->path)) {
                    $missing[] = $image;
                }
            }

            $callback($missing);
        });
    }

    public function ackImage(Image $image, string $path)
    {
        $image->update([
            'is_downloaded' => 1,
            'path' => $path,
        ]);
    }
}
