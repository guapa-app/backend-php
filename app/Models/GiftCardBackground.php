<?php

namespace App\Models;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class GiftCardBackground extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'is_active',
        'uploaded_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Register media collections for background image
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gift_card_backgrounds')->singleFile();
    }

    /**
     * Register media conversions.
     *
     * @return void
     */
    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->performOnCollections('gift_card_backgrounds');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 400, 400)
            ->performOnCollections('gift_card_backgrounds');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 800, 800)
            ->performOnCollections('gift_card_backgrounds');
    }

    // Relationships
    public function uploadedBy()
    {
        return $this->belongsTo(Admin::class, 'uploaded_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        $media = $this->getFirstMedia('gift_card_backgrounds');
        return $media ? $media->getUrl() : null;
    }

    public function getThumbnailUrlAttribute()
    {
        $media = $this->getFirstMedia('gift_card_backgrounds');
        return $media ? $media->getUrl('small') : null;
    }
}
