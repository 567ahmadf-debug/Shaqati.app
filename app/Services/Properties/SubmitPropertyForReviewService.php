<?php

namespace App\Services\Properties;

use App\Models\Property;
use Illuminate\Validation\ValidationException;

class SubmitPropertyForReviewService
{
    public function execute(Property $property): Property
    {
        $allowedStatuses = [
            Property::STATUS_DRAFT,
            Property::STATUS_REJECTED,
        ];

        if (! in_array($property->status, $allowedStatuses, true)) {
            throw ValidationException::withMessages([
                'status' => [
                    'Only draft or rejected properties can be submitted for review.',
                ],
            ]);
        }

        $property->status = Property::STATUS_PENDING_REVIEW;
        $property->save();

        return $property;
    }
}
