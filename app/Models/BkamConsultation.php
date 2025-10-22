<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Enums\BkamConsultaionStatus;
use Hamedov\Taxonomies\HasTaxonomies;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\Listable as ListableTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class BkamConsultation extends Model implements Listable, HasMedia
{
    use HasFactory, ListableTrait, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'taxonomy_id',
        'status',
        'consultation_fee',
        'taxes',
        'payment_status',
        'payment_method',
        'payment_reference',
        'details',
        'medical_history',
        'invoice_url',
        'rejected_at',
        'cancelled_at',
        'completed_at',
    ];

    protected $filterable = [
        'status',
        'user_id'
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'medical_history' => 'array',
        'cancelled_at' => 'datetime',
        'rejected_at' => 'datetime',
        'completed_at' => 'datetime'
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }

    /**
     * Check if the bkam consultation can be cancelled by the user
     *
     * @return bool
     */
    public function canCancel(): bool
    {
        if ($this->status === BkamConsultaionStatus::Rejected) {
            return false;
        }

        // Can't cancel if already completed
        if ($this->status === BkamConsultaionStatus::Approved) {
            return false;
        }

        return true;
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
        $query->with(['media']);
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }
}
