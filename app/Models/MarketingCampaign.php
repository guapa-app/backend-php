<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Enums\MarketingCampaignAudienceType;
use App\Enums\MarketingCampaignChannel;
use App\Enums\MarketingCampaignStatus;
use App\Enums\MarketingCampaignType;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class MarketingCampaign extends Model implements Listable
{
    use HasFactory, ListableTrait;

    protected $fillable = [
        'vendor_id', 'channel', 'audience_type', 'audience_count',
        'message_cost', 'taxes', 'total_cost', 'status',
        'invoice_url', 'campaignable_id', 'campaignable_type',
    ];

    protected $casts = [
        'status' => MarketingCampaignStatus::class,
        'channel' => MarketingCampaignChannel::class,
        'audience_type' => MarketingCampaignAudienceType::class,
        'type' => MarketingCampaignType::class,
    ];

    protected $filterable = [
        'vendor_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function campaignable()
    {
        return $this->morphTo();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'campaign_user');
    }

    public function scopeApplyFilters(Builder $builder, Request $request): Builder
    {
        if ($request->has('vendor_id')) {
            $builder->where('vendor_id', (int) $request->get('vendor_id'));
        }

        return $builder;
    }

    public function scopeWithListRelations(Builder $builder, Request $request): Builder
    {
        return $builder;
    }

    public function scopeWithListCounts(Builder $builder, Request $request): Builder
    {
        return $builder;
    }

    public function scopeWithSingleRelations(Builder $builder): Builder
    {
        return $builder;
    }

    public function scopeWithApiListRelations(Builder $builder): Builder
    {
        return $builder;
    }
}
