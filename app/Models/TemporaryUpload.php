<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;

class TemporaryUpload extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'temporary_uploads';

    protected $fillable = [
        'user_id',
    ];

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('temporary_uploads');
        $this->addMediaCollection('video');
    }

    /**
     * Register media conversions.
     *
     * @return void
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->performOnCollections('temporary_uploads');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('temporary_uploads');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 600, 600)
            ->performOnCollections('temporary_uploads');

        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('video');
    }

    /**
     * Clean up old temporary uploads.
     */
    public static function cleanUp(int $hours = 24)
    {
        static::where('created_at', '<', now()->subHours($hours))->delete();
    }
}
