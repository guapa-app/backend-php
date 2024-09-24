<?php

namespace App\Contracts\Repositories;

use App\Http\Requests\V3_1\AppointmentOfferRequest;
use App\Models\AppointmentOffer;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface AppointmentOfferRepositoryInterface
{
    public function index(): LengthAwarePaginator;

    public function show(int $id): AppointmentOffer|Model;

    public function store(AppointmentOfferRequest $request): AppointmentOffer;

    public function accept(Request $request): Order;

    public function reject(Request $request): void;
}
