<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class AdminPropertyController extends Controller
{
    public function approve(Property $property): JsonResponse
    {
        Gate::authorize('moderate', $property);

        return DB::transaction(function () use ($property) {
            // إعادة قراءة العقار داخل المعاملة لمنع الاعتماد
            // اعتمادًا على حالة قديمة.
            $property = Property::query()
                ->lockForUpdate()
                ->findOrFail($property->id);

            if ($property->status !== 'pending_review') {
                throw ValidationException::withMessages([
                    'status' => [
                        'Only properties pending review can be approved.',
                    ],
                ]);
            }

            $property->status = 'approved';
            $property->save();

            $auditLog = new AuditLog();
            $auditLog->actor_id = request()->user()->id;
            $auditLog->action = 'property.approved';
            $auditLog->auditable()->associate($property);
            $auditLog->metadata = [
                'previous_status' => 'pending_review',
                'new_status' => 'approved',
            ];
            $auditLog->ip_address = request()->ip();
            $auditLog->user_agent = request()->userAgent();
            $auditLog->save();

            return response()->json([
                'message' => 'Property approved successfully.',
                'data' => [
                    'id' => $property->id,
                    'status' => $property->status,
                ],
            ]);
        });
    }
}
