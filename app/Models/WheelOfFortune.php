<?php

namespace App\Models;

use App\Contracts\Listable;
use App\Traits\Listable as ListableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Spatie\Translatable\HasTranslations;

class WheelOfFortune extends Model implements Listable
{
    use ListableTrait, HasTranslations;

    protected $fillable = ['rarity_title', 'probability', 'points'];

    protected $translatable = [
        'rarity_title',
    ];

    /**
     * Attributes to be searched using like operator.
     * @var array
     */
    protected $search_attributes = [
        'rarity_title',
        'probability',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Get the sum of all probabilities except the current one (for updates)
            $query = static::query();

            if ($model->exists) {
                // Exclude the current record's probability when calculating the sum
                $query->where('id', '!=', $model->id);
            }

            $totalProbability = $query->sum('probability');

            // Check if the new total probability will exceed 100%
            if ($totalProbability + $model->probability > 100) {
                throw new \Exception('The total probability cannot exceed 100%.');
            }
        });
    }


    public function loyaltyPointHistories()
    {
        return $this->morphMany(LoyaltyPointHistory::class, 'sourceable');
    }

    public function scopeApplyFilters(Builder $query, Request $request): Builder
    {
        $filter = $request->get('filter');
        if (is_array($filter)) {
            $request = new Request($filter);
        }

        $query->searchLike($request);

        $query->applyDirectFilters($request);

        return $query;
    }

    public function scopeWithListRelations(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithListCounts(Builder $query, Request $request): Builder
    {
        return $query;
    }

    public function scopeWithSingleRelations(Builder $query): Builder
    {
        return $query;
    }
}
