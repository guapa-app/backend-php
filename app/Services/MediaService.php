<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\Support\File;
use Illuminate\Support\Str;
class MediaService
{
    public function handleMedia(Model $record, array $data)
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
            } elseif (is_string($value) && Str::startsWith($value, 'data:')) {
                $this->handleBase64Media($record, $value, $record->getTable());

            }
        }
    }

    private function handleBase64Media($record, string $base64String, string $collectionName): void
    {
        // Validate the Base64 string format
        if (preg_match('/^data:([a-z]+\/[a-z0-9-+.]+);base64,/', $base64String, $matches)) {
            try {
                // Extract MIME type from the Base64 string (e.g., image/png, image/jpeg)
                $mimeType = $matches[1];

                // Extract the file extension based on the MIME type
                $extension = $this->getExtensionFromMimeType($mimeType);

                if (!$extension) {
                    throw new \InvalidArgumentException('Unsupported MIME type: ' . $mimeType);
                }

                // Decode the Base64 file
                $fileData = explode(',', $base64String)[1];
                $decodedFile = base64_decode($fileData);

                if ($decodedFile === false) {
                    throw new \RuntimeException('Base64 decoding failed');
                }

                // Generate a temporary file with the correct extension
                $tempFilePath = sys_get_temp_dir() . '/' . Str::random(10) . '.' . $extension;

                // Save the decoded content to the temporary file
                file_put_contents($tempFilePath, $decodedFile);

                // Add the temporary file to the media collection
                $record->addMedia($tempFilePath)->toMediaCollection($collectionName);
            } catch (\Exception $e) {
                Log::error('Error handling Base64 media: ' . $e->getMessage(), ['base64String' => $base64String]);
                throw new \Exception('Error processing Base64 media: ' . $e->getMessage());
            } finally {
                // Ensure the temporary file is always deleted
                if (isset($tempFilePath) && file_exists($tempFilePath)) {
                    unlink($tempFilePath);
                }
            }
        } else {
            throw new \InvalidArgumentException('Invalid Base64 string format');
        }
    }

    /**
     * Get the file extension based on the MIME type.
     *
     * @param string $mimeType
     * @return string|null
     */
    private function getExtensionFromMimeType(string $mimeType): ?string
    {
        $mimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/svg+xml' => 'svg',
            'application/pdf' => 'pdf',
            // Add more MIME types if needed
        ];

        return $mimeTypes[$mimeType] ?? null;
    }}
