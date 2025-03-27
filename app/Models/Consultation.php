<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Consultation extends Model implements Listable, HasMedia
{
    use ListableTrait, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'vendor_id',
        'appointment_date',
        'appointment_time',
        'type',
        'chief_complaint',
        'medical_history',
        'session_url',
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
        'rejected_at'
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
        'medical_history' => 'array',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

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
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }
}
