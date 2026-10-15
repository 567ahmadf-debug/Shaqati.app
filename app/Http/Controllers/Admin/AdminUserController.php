<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminUserController extends Controller
{
    public function suspend(User $user): JsonResponse
    {
        $actor = request()->user();

        if ($actor->isSuspended()) {
            abort(403, 'Suspended accounts cannot perform this action.');
        }

        if ($actor->is($user)) {
            throw ValidationException::withMessages([
                'user' => ['You cannot suspend your own account.'],
            ]);
        }

        if ($user->isAdmin()) {
            throw ValidationException::withMessages([
                'user' => ['Admin accounts cannot be suspended here.'],
            ]);
        }

        if ($user->isSuspended()) {
            return response()->json([
                'message' => 'The account is already suspended.',
            ], 409);
        }

        DB::transaction(function () use ($actor, $user) {
            $lockedUser = User::query()
                ->lockForUpdate()
                ->findOrFail($user->id);

            if ($lockedUser->isSuspended()) {
                throw ValidationException::withMessages([
                    'user' => ['The account is already suspended.'],
                ]);
            }

            $lockedUser->suspended_at = now();
            $lockedUser->save();

            // إبطال توكنات Sanctum الحالية.
            $lockedUser->tokens()->delete();

            $log = new AuditLog();
            $log->actor_id = $actor->id;
            $log->action = 'user.suspended';
            $log->auditable()->associate($lockedUser);
            $log->metadata = [
                'suspended_at' => $lockedUser->suspended_at
                    ->toISOString(),
            ];
            $log->ip_address = request()->ip();
            $log->user_agent = request()->userAgent();
            $log->save();
        });

        return response()->json([
            'message' => 'User suspended successfully.',
            'data' => [
                'id' => $user->id,
                'suspended_at' => $user->fresh()
                    ->suspended_at,
            ],
        ]);
    }
}
