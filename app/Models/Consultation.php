<?php

namespace App\Models;

use Carbon\Carbon;
use App\Contracts\Listable;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Consultation extends Model implements Listable, HasMedia
{
    use ListableTrait, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'appointment_date',
        'appointment_time',
        'appointment_end_time', // Added new field for end time
        'type',
        'chief_complaint',
        'medical_history',
        'invoice_url',
        'consultation_reason',
        'status',
        'total_amount',
        'tax_amount',
        'consultation_fee',
        'application_fees',
        'payment_status',
        'payment_method',
        'cancelled_at',
        'rejected_at',
        'meeting_provider',
        'session_url',
        'session_password',
        'meeting_data',
        'is_reviewed',
    ];

    protected $filterable = [
        'status',
        'appointment_date',
        'appointment_time',
        'vendor_id',
        'user_id'
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
        'appointment_end_time' => 'datetime', // Cast new field as datetime
        'medical_history' => 'array',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    protected $appends = [
        'can_review',
        'appointment_end_time',
        // 'appointment_time_period' // Added new accessor
    ];


    /**
     * Get the appointment end time.
     *
     * @return string
     */
    public function getAppointmentEndTimeAttribute(): string
    {
        // If end time is set, use it, otherwise calculate from vendor's session duration

        // Get session duration from vendor, default to 60 minutes
        $duration = $this->vendor->session_duration ?? 60;
        return $this->appointment_time->copy()->addMinutes($duration)->format('H:i');

    }

    /**
     * Get the canReview attribute.
     * A user can review a consultation if:
     * 1. They are the consultation owner (user_id matches)
     * 2. The consultation status is completed
     * 3. They haven't already reviewed it
     *
     * @return bool
     */
    public function getCanReviewAttribute(): bool
    {
        // Check if the authenticated user is the consultation owner
        $isOwner = auth()->check() && auth()->id() === $this->user_id;
        // dump(auth()->id());

        // Check if the consultation is completed
        $isCompleted = $this->status === self::STATUS_COMPLETED;

        // Check if the consultation has not been reviewed yet
        $notReviewed = !$this->hasBeenReviewed();

        return $isOwner && $isCompleted && $notReviewed;
    }

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('media');
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
            ->performOnCollections('media');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('media');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 600, 600)
            ->performOnCollections('media');

    }
    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    const STATUS_PENDING = 'pending';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REJECTED = 'rejected';

    /**
     * Check if the consultation can be rejected
     * Vendor can reject a consultation up to 6 hours before the appointment time
     *
     * @return bool
     */
    public function canReject(): bool
    {
        if ($this->status === self::STATUS_CANCELLED || $this->status === self::STATUS_REJECTED) {
            return false;
        }

        $appointmentDateTime = Carbon::parse($this->appointment_date->format('Y-m-d') . ' ' . $this->appointment_time->format('H:i:s'));
        $sixHoursBefore = $appointmentDateTime->copy()->subHours(6);

        return Carbon::now()->lt($sixHoursBefore);

    }

    /**
     * Check if this consultation has been reviewed
     *
     * @return bool
     */
    public function hasBeenReviewed()
    {
        return $this->is_reviewed ||
            $this->reviews()->exists();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'reviewable_id')
            ->where('reviewable_type', Consultation::class);
    }

    /**
     * Check if the consultation can be cancelled by the user
     *
     * @return bool
     */
    public function canCancel(): bool
    {
        if ($this->status === self::STATUS_CANCELLED || $this->status === self::STATUS_REJECTED) {
            return false;
        }

        // Can't cancel if already completed
        if ($this->status === self::STATUS_COMPLETED) {
            return false;
        }

        // Check if consultation is in the past
        $appointmentDateTime = Carbon::parse($this->appointment_date->format('Y-m-d') . ' ' . $this->appointment_time->format('H:i:s'));
        return !$appointmentDateTime->isPast();
    }

    /**
     * Check if the user can join the consultation session
     *
     * @return bool
     */
    public function canJoin(): bool
    {
        // Can only join if status is confirmed and there's a session URL
        if ($this->status !== self::STATUS_CONFIRMED || !$this->session_url) {
            return false;
        }

        // Can join from 10 minutes before until 15 minutes after the appointment time
        $appointmentDateTime = Carbon::parse($this->appointment_date->format('Y-m-d') . ' ' . $this->appointment_time->format('H:i:s'));
        $now = Carbon::now();

        $tenMinutesBefore = $appointmentDateTime->copy()->subMinutes(10);
        $fifteenMinutesAfter = $appointmentDateTime->copy()->addMinutes(15);

        return $now->between($tenMinutesBefore, $fifteenMinutesAfter);
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        return $query;
    }

    public function scopeCurrentVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with(['media']);
        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with(['media']);
        return $query;
    }
    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with(['media']);
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }
}