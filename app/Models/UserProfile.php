<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class UserProfile extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * Attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'firstname', 'lastname', 'gender',
        'birth_date', 'about', 'settings',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'media',
    ];

    protected $casts = [
        'settings' => 'array',
        'birth_date' => 'date',
    ];

    const GENDER = [
        'Male',
        'Female',
        'Other',
    ];

    /**
     * Modify birthdate before save.
     *
     * @param  string  $value
     */
    public function setBirthDateAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['birth_date'] = Carbon::parse($value)->format('Y-m-d');
        }
    }

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatars')->singleFile();
    }

    /**
     * Register media conversions.
     *
     * @return void
     */
    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->performOnCollections('avatars');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('avatars');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_CROP, 600, 600)
            ->performOnCollections('avatars');
    }

    /**
     * Get owner of this profile.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * User profile photo relationship.
     *
     * @return MorphOne
     */
    public function photo(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', 'avatars');
    }
}
