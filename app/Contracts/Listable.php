<?php

namespace App\Contracts;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 * Define methods all models must implement
 */
interface Listable {

	/**
	 * Apply filters to list query
	 * @param  \Illuminate\Database\Eloquent\Builder $builder
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeApplyFilters(Builder $builder, Request $request) : Builder;

	/**
	 * Apply sorting to list query
	 * @param  \Illuminate\Database\Eloquent\Builder $builder
	 * @param  string  $orderBy
	 * @param  string  $orderSort
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeApplyOrderBy(Builder $builder, ?string $orderBy, ?string $orderSort) : Builder;

	/**
	 * Load relations required in model list
	 * @param  \Illuminate\Database\Eloquent\Builder $builder
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithListRelations(Builder $builder, Request $request) : Builder;

	/**
	 * Load relation counts required in model list
	 * @param  \Illuminate\Database\Eloquent\Builder $builder
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithListCounts(Builder $builder, Request $request) : Builder;

	/**
	 * Load relations required in single
	 * @param  \Illuminate\Database\Eloquent\Builder $builder
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWithSingleRelations(Builder $builder) : Builder;

	/**
	 * Apply direct filters to list query
	 * i.e, filters that don't need extra logic
	 * such is where status: 0 | 1
	 * @param  \Illuminate\Database\Eloquent\Builder $query
	 * @param  \Illuminate\Http\Request $request
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeApplyDirectFilters(Builder $query, Request $request) : Builder;

	/**
	 * Get attributes that can be filtered directly
	 * Used by applyDirectFilters scope
	 * @return array
	 */
	public function getFilterableAttributes() : array;
}
