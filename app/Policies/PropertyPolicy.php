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
    /**
     * هل يستطيع المستخدم أو الزائر عرض هذا العقار؟
     */
    public function view(?User $user, Property $property): Response|bool
    {
        // 1. إذا كان العقار معتمداً، نسمح للجميع (حتى الزوار) برؤيته
        if ($property->status === 'approved') {
            return true;
        }

        // 2. إذا لم يكن معتمداً والزائر غير مسجل الدخول، نخفي العقار (404)
        if (! $user) {
            return Response::denyAsNotFound();
        }

        // 3. التحقق مما إذا كان المستخدم هو المالك أو مدير
        $isOwner = ((int) $property->landlord_id === (int) $user->id);
        $isAdmin = $user->isAdmin();

        // 4. السماح للمالك والمدير، وإخفاء العقار عن البقية (404)
        return ($isOwner || $isAdmin) ? true : Response::denyAsNotFound();
    }
}
