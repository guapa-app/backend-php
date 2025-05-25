<?php

namespace App\Models;

use Filament\Panel;
use App\Helpers\Common;
use App\Contracts\Listable;
use Illuminate\Http\Request;
use App\Contracts\FcmNotifiable;
use Laravel\Passport\HasApiTokens;
use Hamedov\Favorites\HasFavorites;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use App\Traits\Listable as ListableTrait;
use Hamedov\Messenger\Traits\Messageable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Traits\FcmNotifiable as FcmNotifiableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class User extends Authenticatable implements Listable, FcmNotifiable, FilamentUser
{
    use HasFactory,
        Notifiable,
        HasApiTokens,
        HasRoles,
        ListableTrait,
        HasFavorites,
        FcmNotifiableTrait,
        Messageable;

    /**
     * User account statuses.
     */
    public const STATUS_ACTIVE = 'Active';
    public const STATUS_CLOSED = 'Closed';
    public const STATUS_DELETED = 'Deleted';

    const FAVORITE_TYPES = [
        'vendor',
        'product',
        'post',
        'offer',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'phone_verified_at',
        'country_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'roles',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Attributes that can be filtered directly
     * using values from client without any logic.
     *
     * @var array
     */
    protected $filterable_attributes = [
        'status',
    ];

    /**
     * Attributes to be searched using like operator.
     *
     * @var array
     */
    protected $search_attributes = [
        'name',
        'email',
        'phone',
    ];

    /**
     * Attributes to be appended to each user.
     *
     * @var array
     */
    protected $appends = [
        'role',
    ];

    /**
     * Define guard name for the roles package.
     *
     * @var  string
     */
    protected $guard_name = 'api';

    public function isAdmin(): bool
    {
        return false;
    }

    public function findForPassport($username)
    {
        $isEmail = filter_var($username, FILTER_VALIDATE_EMAIL);

        return $isEmail ?
            $this->where('email', $username)->first() :
            $this->whereIn('phone', Common::getPhoneVariations($username))
            ->first();
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'user.' . $this->id;
    }

    public function getRoleAttribute()
    {
        $relations = $this->getRelations();

        return isset($relations['roles']) ? $relations['roles']->pluck('name') : [];
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function photo(): HasOneThrough
    {
        return $this->hasOneThrough(Media::class, UserProfile::class, 'user_id', 'model_id')
            ->where('model_type', 'profile');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(History::class);
    }

    public function support_messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function userVendor(): HasOne
    {
        return $this->hasOne(UserVendor::class);
    }

    public function vendor(): HasOneThrough
    {
        return $this->hasOneThrough(Vendor::class, UserVendor::class, 'user_id', 'id', 'id', 'vendor_id');
    }

    public function userVendors(): HasMany
    {
        return $this->hasMany(UserVendor::class);
    }

    public function getUserVendorsIdsAttribute(): array
    {
        return $this->userVendors()->pluck('vendor_id')->toArray();
    }

    public function hasVendor(int $vendorId): bool
    {
        return (bool) $this->userVendors()->where([
            'vendor_id' => $vendorId,
        ])->exists();
    }

    public function managerVendorId(): int|null
    {
        return $this->userVendors()
            ->where('role', 'manager')
            ->first()?->vendor_id;
    }

    public function hasAnyVendor(array $vendorIds): bool
    {
        return $this->userVendors()->whereIn('vendor_id', $vendorIds)->count() > 0;
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function pointsWallet()
    {
        return $this->hasOne(PointsWallet::class);
    }

    public function loyaltyPointHistory()
    {
        return $this->hasMany(LoyaltyPointHistory::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function loyaltyPointHistories()
    {
        return $this->morphMany(LoyaltyPointHistory::class, 'sourceable');
    }

    public function adminUserPointHistory(): HasMany
    {
        return $this->hasMany(AdminUserPointHistory::class);
    }

    public function scopeCurrentVendor($query, $value): void
    {
        $query->whereRelation('userVendors', 'vendor_id', '=', $value);
    }

    public function scopeActive($query): void
    {
        $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeClosed($query): void
    {
        $query->where('status', self::STATUS_CLOSED);
    }

    public function loadRoles(): void
    {
        // Load user role names directly before sending response to client
        $roles = $this->roles;
        $this->setRelation('roles', $roles->pluck('name'));
    }

    public function loadProfileFields(): void
    {
        $this->load('profile', 'profile.photo', 'roles');

        if (isset($this->profile)) {
            $this->profile->about = strip_tags($this->profile->about);
        }
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

        if ($request->has('gender')) {
            $query->gender($request->get('gender'));
        }

        if ($request->has('vendor_id')) {
            $query->whereHas('userVendors', function ($q) use ($request) {
                $q->where('vendor_id', $request->get('vendor_id'));
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
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        return $query->with([
            'profile',
            'profile.photo',
            'roles',
        ]);
    }

    public function scopeGender($query, $gender): Builder
    {
        $query->addSelect('users.*', 'user_profiles.user_id')
            ->join('user_profiles', function ($join) use ($gender) {
                $join->on('users.id', '=', 'user_profiles.user_id');
                $join->where('user_profiles.gender', $gender);
            });

        return $query;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->userVendors->count() && $this->hasVerifiedEmail() && !is_null($this->phone_verified_at);
    }

    /**
     * Get the entity's notifications.
     *
     * @return MorphMany
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->latest();
    }

    public function appointmentOffers(): HasMany
    {
        return $this->hasMany(AppointmentOffer::class);
    }

    /**
     * User Profile
     * Get user profile data.
     *
     * @return UserProfile
     */
    public function myProfile(): UserProfile
    {
        // Check if the user has a UserProfile
        if (!$this->profile) {
            // Create a new profile if it doesn't exist
            $profile = $this->profile()->create([]);
        } else {
            $profile = $this->profile;
        }

        return $profile;
    }

    /**
     * User Wallet
     * Get user wallet data.
     *
     * @return Wallet
     */
    public function myWallet(): Wallet
    {
        // Check if the user has a wallet
        if (!$this->wallet) {
            // Create a new wallet if it doesn't exist
            $wallet = $this->wallet()->create([
                'balance' => 0,
                'points' => 0,
            ]);
        } else {
            $wallet = $this->wallet;
        }

        return $wallet;
    }

    /**
     * User Points Wallet
     * Get user wallet data.
     *
     * @return PointsWallet
     */
    public function myPointsWallet(): PointsWallet
    {
        // Check if the user has a points wallet

        if (!$this->pointsWallet) {
            // Create a new points wallet if it doesn't exist
            $pointsWallet = $this->pointsWallet()->create([
                'points' => 0,
            ]);
        } else {
            $pointsWallet = $this->pointsWallet;
        }

        return $pointsWallet;
    }
}
