<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'birth_date', 'about', 'referral_code' ,'settings',
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

    protected static function boot()
    {
        parent::boot();

        static::created(function ($profile) {
            if (!$profile->referral_code) {
                $profile->referral_code = self::generateUniqueReferralCode();
                $profile->save();
            }
        });
    }

    /**
     * Generate a unique referral code
     *
     * @return string
     */
    public static function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8)); // 8-character referral code
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

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

    /**
     * Referral Code
     *
     * @return void
     */
    public function getReferralCode()
    {
        if (!$this->referral_code) {
            $this->referral_code = self::generateUniqueReferralCode();
            $this->save();
        }

        return $this->referral_code;
    }
}
