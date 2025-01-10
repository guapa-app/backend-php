<?php

namespace App\Http\Controllers\Api\User\V3_1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\V3_1\User\MediaUploadRequest;
use App\Http\Resources\User\V3_1\MediaCollection;
use App\Models\TemporaryUpload;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class MediaController extends BaseApiController
{
    /**
     * Upload Temp media
     */
    public function uploadTemporaryMedia(MediaUploadRequest $request): MediaCollection
    {
        $tempModel = TemporaryUpload::create(['user_id' => auth()->id()]);

        // Handle video upload
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            // Check if file was uploaded successfully
            if ($video && $video->isValid()) {
                try {
                    $tempModel->addMedia($video)
                        ->toMediaCollection('video');
                    return MediaCollection::make($tempModel->getFirstMedia('video'));
                } catch (\Exception $e) {
                    \Log::error('Video upload failed: ' . $e->getMessage());
                    throw new \Exception('Failed to process video upload');
                }
            }
        }

        // Handle image uploads
        if ($request->has('media')) {
            $collection = 'temporary_uploads';
            $media = $request->media;

            foreach ($media as $value) {
                if ($value instanceof UploadedFile) {
                    $tempModel->addMedia($value)->toMediaCollection($collection);
                } elseif (is_string($value) && Str::startsWith($value, 'data:')) {
                    $tempModel->addMediaFromBase64($value)->toMediaCollection($collection);
                }
            }

            return MediaCollection::make($tempModel->getMedia($collection));
        }

        // If no files were uploaded, return empty collection
        return MediaCollection::make([]);
    }
}
