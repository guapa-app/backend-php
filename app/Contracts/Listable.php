<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Define methods all models must implement.
 */
interface Listable
{
    /**
     * Apply filters to list query.
     * @param Builder $builder
     * @param Request $request
     * @return Builder
     */
    public function scopeApplyFilters(Builder $builder, Request $request): Builder;

    /**
     * Apply sorting to list query.
     * @param Builder $builder
     * @param string $orderBy
     * @param string $orderSort
     * @return Builder
     */
    public function scopeApplyOrderBy(Builder $builder, ?string $orderBy, ?string $orderSort): Builder;

    /**
     * Load relations required in model list.
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithListRelations(Builder $builder, Request $request): Builder;

    /**
     * Load relation counts required in model list.
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithListCounts(Builder $builder, Request $request): Builder;

    /**
     * Load relations required in single.
     * @param Builder $builder
     * @return Builder
     */
    public function scopeWithSingleRelations(Builder $builder): Builder;

    /**
     * Apply direct filters to list query
     * i.e, filters that don't need extra logic
     * such is where status: 0 | 1.
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    public function scopeApplyDirectFilters(Builder $query, Request $request): Builder;

    /**
     * Get attributes that can be filtered directly
     * Used by applyDirectFilters scope.
     * @return array
     */
    public function getFilterableAttributes(): array;
}
