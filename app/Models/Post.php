<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Likable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Post extends Model implements Listable, HasMedia
{
    use HasFactory, InteractsWithMedia, ListableTrait, Likable;

    public const STATUSES = [
        1 => 'Published',
        2 => 'Draft',
        3 => 'Pending',
    ];

    protected $fillable = [
        'country_id', 'admin_id', 'category_id', 'title',
        'content', 'status', 'youtube_url', 'tag_id',
        'type','product_id','stars','user_id','show_user','service_date',
        'vendor_id','vendor_name'
    ];

    protected $filterable = [
        'admin_id', 'category_id', 'status', 'tag_id','type','product_id','stars','user_id','vendor_id'
    ];

    protected $search_attributes = [
        'title', 'content','vendor_name'
    ];

    protected $appends = [
        'likes_count', 'is_liked','comments_count'
    ];

    /**
     * Register media collections.
     *
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('posts');
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
            ->performOnCollections('posts', 'before', 'after');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->performOnCollections('posts', 'before', 'after');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 600, 600)
            ->performOnCollections('posts', 'before', 'after');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class)->withDefault();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Taxonomy::class, 'category_id')->withDefault();
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class)->withDefault();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withDefault();
    }

    public function socialMedia(): BelongsToMany
    {
        return $this->belongsToMany(SocialMedia::class)
            ->withPivot('link');
    }

    // this relation created for admin panel.
    public function postSocialMedia(): HasMany
    {
        return $this->hasMany(PostSocialMedia::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function voteOptions(): HasMany
    {
        return $this->hasMany(VoteOption::class);
    }

    public function userVotes(): HasMany
    {
        return $this->hasMany(UserVote::class);
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->dateRange($request->get('startDate'), $request->get('endDate'));

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        // if request has type = blog else get all without blog type
        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        } else {
            $query->where('type', '!=', 'blog');
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        $query->with('admin', 'category');

        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with('admin', 'media', 'category');
        if (!$request->has('type') || $request->get('type') !== 'blog') {
            $query->with('user','product','voteOptions');
        }
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        $query->withCount('comments');

        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with('admin', 'media', 'category', 'socialMedia', 'socialMedia.icon');
        $query->withCount('comments');

        return $query;
    }
}
