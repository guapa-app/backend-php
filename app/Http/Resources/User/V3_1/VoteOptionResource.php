<?php

namespace App\Http\Resources\User\V3_1;

use Illuminate\Http\Resources\Json\JsonResource;

class VoteOptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'option_text' => $this->option,
            'votes_count' => $this->votes_count,
            'has_voted' => $this->userVotes->where('user_id', auth()->id())->count() > 0
        ];
    }
}
