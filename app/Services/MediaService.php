<?php

namespace App\Services;

use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class MediaService
{
    function handleMedia(Model $record, array $data)
    {
        // New media must be specified or old media to keep
        if (!isset($data['media']) && !isset($data['keep_media'])) {
            return $record;
        }

        // Remove media user doesn't want to keep
        $keep_media = $data['keep_media'] ?? [];
        $record->media()->whereNotIn('id', $keep_media)->delete();

        // Add new media
        foreach ($data['media'] ?? [] as $key => $value) {
            if ($value instanceof UploadedFile) {
                $record->addMedia($value)->toMediaCollection($record->getTable());
            }
        }
    }
}
