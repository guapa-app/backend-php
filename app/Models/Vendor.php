<?php

namespace App\Models;

use App\Contracts\HasReviews;
use App\Traits\HasAddresses;
use App\Traits\Likable;
use App\Traits\Listable as ListableTrait;
use App\Traits\Reviewable;
use Hamedov\Messenger\Traits\Messageable;
use Hamedov\Taxonomies\HasTaxonomies;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Redis;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Vendor extends Model implements HasMedia, HasReviews
{
    use HasFactory, ListableTrait, InteractsWithMedia, Messageable,
        HasTaxonomies, HasAddresses, Reviewable, Likable, Notifiable, SoftDeletes;

    public const TYPES = [
        0 => 'hospital',
        1 => 'clinic',
        2 => 'doctor',
        3 => 'therapist ',
        4 => 'specialist',
        5 => 'distributor',
        6 => 'dietitian',
        7 => 'artist',
        8 => 'nail specialist ',
        9 => 'hair specialist',
        10 => 'tattoo artists',
        11 => 'microblading specialist',
        12 => 'massage therapist',
    ];

    public const STATUSES = [
        0 => 'closed',
        1 => 'active',
    ];

    protected $fillable = [
        'name', 'email', 'status', 'verified',
        'phone', 'about', 'whatsapp', 'twitter',
        'instagram', 'snapchat', 'type', 'working_days',
        'working_hours', 'website_url', 'known_url', 'tax_number',
        'cat_number', 'reg_number', 'health_declaration',
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
        'verified' => 'boolean',
    ];

    protected $appends = [
        'specialty_ids', 'likes_count', 'is_liked',
        'views_count', 'shares_count', 'work_days',
        'shared_link',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     * @var array
     */
    protected $filterable = [
        'status', 'verified',
    ];

    /**
     * Attributes to be searched using like operator.
     * @var array
     */
    protected $search_attributes = [
        'name', 'email', 'phone', 'about',
    ];

    public function getSpecialtyIdsAttribute()
    {
        $relations = $this->getRelations();
        if (!empty($relations['specialties'])) {
            return $relations['specialties']->pluck('id')->toArray();
        }

        return [];
    }

    public function getSharedLinkAttribute()
    {
        return $this->shareLink?->link;
    }

    public function getSharesCountAttribute()
    {
        return (int) Redis::hget("vendor:{$this->id}", 'shares_count');
    }

    public function getViewsCountAttribute()
    {
        return (int) Redis::hget("vendor:{$this->id}", 'views_count');
    }

    public function getWorkDaysAttribute()
    {
        $relations = $this->getRelations();

        if (isset($relations['workDays']) && is_array($relations['workDays'])) {
            return $relations['workDays'];
        }

        $days = isset($relations['workDays']) ?
            $relations['workDays']->pluck('day') :
            [];
        $this->setRelation('workDays', $days);

        return $days;
    }

    /**
     * Register media collections.
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')->singleFile();
        $this->addMediaCollection('contract')->singleFile();
    }

    /**
     * Register media conversions.
     * @return void
     */
    public function registerMediaConversions(BaseMedia $media = null): void
    {
        $this->addMediaConversion('small')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->performOnCollections('logos');

        $this->addMediaConversion('medium')
            ->fit(Manipulations::FIT_MAX, 300, 300)
            ->performOnCollections('logos');

        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_MAX, 600, 600)
            ->performOnCollections('logos');
    }

    /**
     * The channels the vendor receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'vendor.' . $this->id;
    }

    /**
     * Route notifications for the FCM channel.
     * return array of fcm tokens to send the notification to.
     *
     * @param Notification $notification
     *
     * @return array
     */
    public function routeNotificationForFcm(Notification $notification): array
    {
        return Device::whereIn('user_id', function ($query) {
            $query->select('user_id')->from('user_vendor')->where('vendor_id', $this->id);
        })->where('user_type', 'user')->pluck('fcmtoken')->toArray();
    }

    public function photo()
    {
        return $this->logo();
    }

    public function shareLink()
    {
        return $this->morphOne(ShareLink::class, 'shareable');
    }

    /**
     * Vendor logo relationship.
     * @return MorphOne
     */
    public function logo(): MorphOne
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', 'logos');
    }

    public function contract(): MorphOne
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', 'contract');
    }

    public function socialMedia(): BelongsToMany
    {
        return $this->belongsToMany(SocialMedia::class)
            ->withPivot('link')
            ->withTimestamps();
    }

    public function users(): HasMany
    {
        return $this->hasMany(UserVendor::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(VendorClient::class);
    }

    public function orders_order(): HasMany
    {
        return $this->hasMany(Order::class)
            ->where('status', 'Pending')
            ->whereHas('items', function ($q) {
                $q->where('appointment', null);
            });
    }

    public function orders_consultations(): HasMany
    {
        return $this->hasMany(Order::class)
            ->where('status', 'Pending')
            ->whereHas('items', function ($q) {
                $q->where('appointment', '!=', null);
            });
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'email')->withTimestamps();
    }

    public function managers(): BelongsToMany
    {
        return $this->staff()->wherePivot('role', 'manager');
    }

    public function doctors(): BelongsToMany
    {
        return $this->staff()->wherePivot('role', 'doctor');
    }

    public function specialties()
    {
        return $this->taxonomies('specialty');
    }

    public function allProducts()
    {
        return $this->hasMany(Product::class);
    }

    public function productsHasOffers()
    {
        return $this->allProducts()->whereHas('offer');
    }

    public function products()
    {
        return $this->allProducts()->where('type', 'product');
    }

    public function services()
    {
        return $this->allProducts()->where('type', 'service');
    }

    public function offers()
    {
        return $this->hasManyThrough(Offer::class, Product::class);
    }

    public function activeOffers()
    {
        return $this->offers()->active();
    }

    public function workDays()
    {
        return $this->hasMany(WorkDay::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function influencers(): HasMany
    {
        return $this->hasMany(Influencer::class);
    }

    public function hasUser(User $user, $role = null)
    {
        return $this->users()->where([
                'user_id' => $user->id,
            ])->when($role != null, function ($query) use ($role) {
                $query->where('role', $role);
            })->first() != null;
    }

    public function hasManager(User $user)
    {
        return $this->hasUser($user, 'manager');
    }

    public function hasDoctor(User $user)
    {
        return $this->hasUser($user, 'doctor');
    }

    public function hasStaff(User $user)
    {
        return $this->hasUser($user, 'staff');
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->where('id', $value);
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

        if ($request->has('specialty_ids')) {
            $query->hasAnyTaxonomy((array) $request->get('specialty_ids'));
        }

        // Get products nearby specific location by specific distance
        if ($request->has('lat') && $request->has('lng')) {
            $query->nearBy($request->get('lat'), $request->get('lng'), $request->get('distance'));
        }
        // get vendors for specific city
        if ($request->has('city_id')) {
            $query->hasCity((int) $request->get('city_id'));
        }

        // Only logged-in users/admins can use this filter
        $user = auth()->user();

        if ($request->has('user_id') && $user) {
            $userId = $user->isAdmin() ? (int) $request->get('user_id') : $user->id;

            $query->whereHas('users', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->when(!$user->isAdmin(), function ($q) {
                $q->with('addresses');
            });
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request): Builder
    {
        $query->with('logo');
        $query->withCount('products', 'activeOffers', 'services');

        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        $query->withCount('users');

        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        $query->with('logo', 'staff', 'specialties', 'workDays', 'appointments', 'addresses', 'socialMedia', 'socialMedia.icon');
        $query->withCount('products', 'activeOffers', 'services', 'orders_order', 'orders_consultations');

        if (\request()->load_products) {
            $query->with([
                'products' => function ($query) {
                    $query->take(5);
                },
                'products.taxonomies',
                'products.media',
                'products.offer',
                'products.offer.image',

                'services' => function ($query) {
                    $query->take(5);
                },
                'services.taxonomies',
                'services.media',
                'services.offer',
                'services.offer.image',

                'productsHasOffers' => function ($query) {
                    $query->take(5);
                },
                'productsHasOffers.taxonomies',
                'productsHasOffers.media',
                'productsHasOffers.offer',
                'productsHasOffers.offer.image',

            ]);
        }

        return $query;
    }
}
