<?php

namespace App\Models;

use App\Contracts\FcmNotifiable;
use App\Contracts\Listable;
use App\Helpers\Common;
use App\Traits\FcmNotifiable as FcmNotifiableTrait;
use App\Traits\Listable as ListableTrait;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Hamedov\Favorites\HasFavorites;
use Hamedov\Messenger\Traits\Messageable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Listable, FcmNotifiable, FilamentUser
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles,
        ListableTrait, HasFavorites, FcmNotifiableTrait,
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
     * @var array
     */
    protected $filterable_attributes = [
        'status',
    ];

    /**
     * Attributes to be searched using like operator.
     * @var array
     */
    protected $search_attributes = [
        'name', 'email', 'phone',
    ];

    /**
     * Attributes to be appended to each user.
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

    public function isAdmin()
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

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function photo()
    {
        return $this->hasOneThrough(Media::class, UserProfile::class, 'user_id', 'model_id')
            ->where('model_type', 'profile');
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function support_messages()
    {
        return $this->hasMany(SupportMessage::class);
    }

    public function userVendors()
    {
        return $this->hasMany(UserVendor::class);
    }

    public function getUserVendorsIdsAttribute(): array
    {
        return $this->userVendors()->pluck('vendor_id')->toArray();
    }

    public function hasVendor(int $vendorId)
    {
        return (bool) $this->userVendors()->where([
            'vendor_id' => $vendorId,
        ])->exists();
    }

    public function managerVendorId()
    {
        return $this->userVendors()
            ->where('role', 'manager')
            ->first()?->vendor_id;
    }

    public function hasAnyVendor(array $vendorIds): bool
    {
        return $this->userVendors()->whereIn('vendor_id', $vendorIds)->count() > 0;
    }

    public function scopeCurrentVendor($query, $value)
    {
        return $query->whereRelation('userVendors', 'vendor_id', '=', $value);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function loadRoles()
    {
        // Load user role names directly before sending response to client
        $roles = $this->roles;
        $this->setRelation('roles', $roles->pluck('name'));
    }

    public function loadProfileFields()
    {
        $this->load('profile', 'profile.photo', 'roles');
        $this->profile->about = strip_tags($this->profile->about);
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
            'profile', 'profile.photo', 'roles',
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
}
