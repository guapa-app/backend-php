<?php

namespace App\Services;

use Illuminate\Foundation\Auth\User;

class Cosmo
{
    protected $user;

    public function __construct()
    {
        $this->setCurrentUser();
    }

    public function user(): ?User
    {
        return $this->user;
    }

    public function isAdmin(): bool
    {
        return $this->user && $this->user->isAdmin();
    }

    /**
     * Set current user based on api or admin.
     *
     * @return void
     */
    public function setCurrentUser(?User $user = null): void
    {
        if ($user != null) {
            $this->user = $user;

            return;
        }

        $route = request()->route();
        if (!$route) {
            $this->user = auth()->user();

            return;
        }

        $action = $route->getAction();
        $isAdmin = isset($action['prefix']) &&
            strpos($action['prefix'], 'admin-api') === 0;
        $guard = $isAdmin ? 'admin' : 'api';

        $this->user = auth($guard)->user();
    }
}
