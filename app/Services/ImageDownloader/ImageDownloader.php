<?php

namespace App\Services\ImageDownloader;

use App\Repositories\Image\ImageRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageDownloader
{
    public function __construct(
        protected ImageRepository $imageRepository,
    )
    {

    }

    public function fixMissingFiles(): void
    {
        $this->imageRepository->operateMissingFilesInBatches(function ($batch) {
            $this->downloadImages($batch);
        });
    }

    public function downloadNewImages(): void
    {
        $this->imageRepository->operateNewFilesInBatches(function ($batch) {
            $this->downloadImages($batch);
        });
    }

    protected function downloadImages(iterable $images, int $concurrency = 20)
    {
        $client = new Client();
        $requests = function ($images) {
            foreach ($images as $image) {
                yield new Request('GET', $image->external_url);
            }
        };

        $saved = 0;
        $errors = [];

        $pool = new Pool($client, $requests($images), [
            'concurrency' => $concurrency,
            'fulfilled' => function ($response, $index) use ($images, &$saved) {
                $image = $images[$index];
                $path = 'images/' . $image->id . "/" . md5($image->external_url) . '.png';
                Storage::put($path, $response->getBody()->getContents());
                $this->imageRepository->ackImage($image, $path);
                $saved++;
            },
            'rejected' => function ($reason, $index) use ($images, &$errors) {
                $errors[] = [
                    'id' => $images[$index]->id,
                    'url' => $images[$index]->external_url,
                    'error' => $reason->getMessage(),
                ];
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        Log::debug("Results of image download", [
            'saved' => $saved,
            'errors' => $errors,
        ]);
    }


}
