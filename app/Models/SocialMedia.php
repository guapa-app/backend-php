<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class SocialMedia extends Model implements Listable, HasMedia
{
    use ListableTrait, InteractsWithMedia;

    protected $fillable = [
        'name'
    ];

    public function vendors(): BelongsToMany
    {
        return $this->belongsToMany(Vendor::class)
            ->withPivot('link')
            ->withTimestamps();
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class)
            ->withPivot('link');
    }

    public function icon(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'social_media_icons');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('social_media_icons')->singleFile();
    }

    /**
     * Register media conversions.
     * @param BaseMedia|null $media
     * @return void
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_MAX, 300, 500)
            ->performOnCollections('social_media_icons');
    }


    public function scopeApplyFilters(Builder $builder, Request $request): Builder
    {
        return $builder;
    }

    public function scopeWithListRelations(Builder $builder, Request $request): Builder
    {
        return $builder->with('icon');
    }

    public function scopeWithListCounts(Builder $builder, Request $request): Builder
    {
        return $builder;
    }

    public function scopeWithApiListRelations(Builder $builder, Request $request): Builder
    {
        return $builder->with('icon');
    }

    public function scopeWithSingleRelations(Builder $builder): Builder
    {
        return $builder->with('icon');
    }
}
