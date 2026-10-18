<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ProfileResource;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'profile_completed' => (bool) $user->profile_completed,
            'profile' => $user->profile
                ? new ProfileResource($user->profile)
                : null,
        ]);
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        $profile = DB::transaction(function () use ($user, $data) {
            // استرجاع الملف حتى لو كان محذوفاً حذفاً ناعماً.
            $profile = $user->profile()
                ->withTrashed()
                ->first();

            if ($profile === null) {
                $profile = $user->profile()->make();
            } elseif ($profile->trashed()) {
                $profile->restore();
            }

            $profile->fill($data);
            $profile->save();


            $user->profile_completed = true;
            $user->save();

            return $profile;
        });

        return response()->json([
            'message' => 'Profile updated successfully.',
            'profile_completed' => (bool) $user->fresh()->profile_completed,
            'profile' => new ProfileResource($profile),
        ]);
        
    }


    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'Profile not found.'
            ], 404);
        }

        $profile->delete();
        $user->profile_completed = false;
        $user->save();

        return response()->json([
            'message' => 'Profile deleted successfully.'
        ]);
    }
}
