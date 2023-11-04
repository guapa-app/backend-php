<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable implements Listable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasPermissions, ListableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'roles',
    ];

    protected $guard_name = 'admin';

    /**
     * Attributes to be searched using like operator
     * @var array
     */
    protected $search_attributes = [
        'name', 'email',
    ];

    protected $appends = [
        'role',
    ];

    public function isAdmin()
    {
        return true;
    }

    public function getRoleAttribute()
    {
        $role = $this->roles()->first();
        return $role ? $role->name : 'None';
    }

    /**
     * User profile photo relationship
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function photo(): MorphOne
    {
        return $this->morphOne('App\Models\Media', 'model')
            ->where('collection_name', 'avatars');
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');

        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        if ($request->has('role')) {
            $query->role(strtolower($request->get('role')));
        }

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request) : Builder
    {
        $query->with('roles');
        return $query;
    }

    public function scopeWithApiListRelations(Builder $query, Request $request) : Builder
    {
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request) : Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query) : Builder
    {
        $query->with('roles');
        return $query;
    }
}
