<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\PropertyResource;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PropertyController extends Controller
{
    public function store(StorePropertyRequest $request): JsonResponse
    {

        $property = $request->user()
            ->properties()
            ->create($request->validated());

        return response()->json([
            'message' => 'Property created successfully.',
            'data' => new PropertyResource($property),
        ], 201);
    }

    public function update(UpdatePropertyRequest $request, Property $property): JsonResponse
    {

        Gate::authorize('update', $property);

        $property->fill($request->validated());
        $property->save();

        return response()->json([
            'message' => 'Property updated successfully.',
            'data' => new PropertyResource($property),
        ]);
    }

    public function destroy(Property $property): JsonResponse
    {

        Gate::authorize('delete', $property);

        $property->delete();

        return response()->json([
            'message' => 'Property deleted successfully.',
        ]);
    }
    public function show(Request $request, Property $property): JsonResponse
    {
        Gate::authorize('view', $property);

        return response()->json([
            'data' => new PropertyResource($property),
        ]);
    }
}
