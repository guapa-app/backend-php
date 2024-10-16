<?php

namespace App\Http\Resources\User\V3_1;


class UserWithPointsResource extends UserResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'points' => (int) $this->myPointsWallet()->points
        ]);
    }
}
