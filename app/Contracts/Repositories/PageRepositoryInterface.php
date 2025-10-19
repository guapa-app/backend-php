<?php

namespace App\Contracts\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface PageRepositoryInterface
{
    /**
     * Get all pages.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection;
}
