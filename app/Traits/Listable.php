<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Listable
{
    public function scopeApplyDirectFilters(Builder $query, Request $request): Builder
    {
        // Return if no attributes can be filtered directly
        $attributes = $this->getFilterableAttributes();

        if (!is_array($attributes) || empty($attributes)) {
            return $query;
        }

        // Add the id by default
        $attributes[] = 'id';

        $data = $request->only($attributes);

        foreach ($data as $key => $value) {
            $method = is_array($value) ? 'whereIn' : 'where';
            $query->$method($this->getConstraintKey($key), $value);
        }

        return $query;
    }

    public function scopeDateRange(Builder $query, ?string $minDate, ?string $maxDate): Builder
    {
        $key = $this->getConstraintKey('created_at');

        if (isset($minDate)) {
            $query->whereDate($key, '>=', $minDate);
        }

        if (isset($maxDate)) {
            $query->whereDate($key, '<=', $maxDate);
        }

        return $query;
    }

    public function scopeSearchLike(Builder $query, Request $request): Builder
    {
        $keyword = $this->getSearchKeyword($request);
        $attributes = $this->getSearchAttributes();

        if ($keyword == null || empty($attributes)) {
            return $query;
        }

        $query->where(function ($q) use ($keyword, $attributes) {
            foreach ($attributes as $key) {
                $q->orWhere($this->getConstraintKey($key), 'LIKE', '%' . $keyword . '%');
            }
        });

        return $query;
    }

    public function scopeApplyOrderBy(Builder $query, ?string $sort, ?string $order): Builder
    {
        $field = isset($sort) ? $this->getConstraintKey($sort) : $this->getConstraintKey('id');
        $order = in_array(strtolower($order), ['asc', 'desc']) ? $order : 'desc';
        $query->orderBy($field, $order);
        return $query;
    }

    public function getFilterableAttributes(): array
    {
        if (!isset($this->filterable) || !is_array($this->filterable)) {
            return [];
        }

        return $this->filterable;
    }

    public function getSearchAttributes(): array
    {
        if (!isset($this->search_attributes) ||
            !is_array($this->search_attributes)) {
            return [];
        }

        return $this->search_attributes;
    }

    public function getSearchKeyword(Request $request): ?string
    {
        if ($request->has('keyword')) {
            return $request->get('keyword');
        }

        if ($request->has('q')) {
            return $request->get('q');
        }

        return null;
    }

    /**
     * Get constraint key based on table name
     * of current model
     * @param string $key
     * @return string
     */
    public function getConstraintKey(string $key): string
    {
        return $this->getTable() . '.' . $key;
    }
}
