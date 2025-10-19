<?php

namespace App\Repositories\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WheelOfFortune;
use App\Contracts\Repositories\WheelOfFortuneInterface;

class WheelOfFortuneRepository extends EloquentRepository implements WheelOfFortuneInterface
{
    /**
     * Construct an instance of the repo.
     *
     * @param WheelOfFortune $model
     */
    public function __construct(WheelOfFortune $model)
    {
        parent::__construct($model);
    }

    public function all(Request $request): object
    {
        $query = WheelOfFortune::query();

        if ($request->has('perPage')) {
            return $query->paginate($request->perPage);
        } else {
            return $query->get();
        }
    }
}
