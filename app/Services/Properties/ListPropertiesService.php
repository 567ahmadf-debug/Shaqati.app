<?php

namespace App\Services\Properties;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

class ListPropertiesService
{
    public function execute(?string $search = null): Collection
    {
        $query = Property::query()
            ->where('status', 'approved');

        if ($search !== null && $search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%");
            });
        }

        return $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(20)
            ->get();
    }
}