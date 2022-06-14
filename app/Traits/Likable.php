<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

trait Likable {

	public function getLikesCountAttribute()
    {
        return (int) Redis::scard($this->getLikesCacheKey());
    }

    public function getIsLikedAttribute()
    {
        $user = app('cosmo')->user();
        if ( ! $user || $user->isAdmin()) {
            return false;
        }

        return (bool) Redis::sismember($this->getLikesCacheKey(), $user->id);
    }

	public function addLike(User $user)
	{
        Redis::sadd($this->getLikesCacheKey(), $user->id);
	}

	public function removeLike(User $user)
	{
		Redis::srem($this->getLikesCacheKey(), $user->id);
	}

	/**
     * Get Model lowercase name
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return string
     */
    public function getLikesCacheKey(): string
    {
        $className = (new \ReflectionClass($this))->getShortName();
        return strtolower($className) . ':' . $this->id . ':likes';
    }
}