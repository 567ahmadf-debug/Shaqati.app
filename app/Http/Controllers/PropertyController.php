<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePropertyRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Property;
use Illuminate\Http\JsonResponse;

class PropertyController extends Controller
{
    public function store( StorePropertyRequest $request): JsonResponse {

        $property = $request->user()
            ->properties()
            ->create($request->validated());

        return response()->json([
            'message' => 'Property created successfully.',
            'data' => new ProfileResource($property),
        ], 201);
    }
}

