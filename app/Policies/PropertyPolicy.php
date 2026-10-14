<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    /**
     * هل يستطيع المستخدم تعديل هذا العقار؟
     */
    public function update(User $user, Property $property): bool
    {
        return $user->role === 'landlord'
            && (int) $property->landlord_id === (int) $user->id;
    }

    /**
     * هل يستطيع المستخدم حذف هذا العقار؟
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->role === 'landlord'
            && (int) $property->landlord_id === (int) $user->id;
    }

    public function moderate(User $user, Property $property): bool
    {
        return $user->isAdmin();
    }
}
