<?php

namespace App\Policies;

use App\Models\RentalRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class RentalRequestPolicy
{
    public function view(User $user, RentalRequest $rentalRequest): bool
    {
        return (int) $rentalRequest->tenant_id === (int) $user->id
            || (int) $rentalRequest->landlord_id === (int) $user->id;
    }

    public function respond(User $user, RentalRequest $rentalRequest): bool
    {
        return $user->isLandlord()
            && (int) $rentalRequest->landlord_id === (int) $user->id;
    }
}
