<?php

namespace App\Services\Properties;

use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

class ListPropertiesService
{
    public function execute(array $filters = []): Collection
    {
        $query = Property::query()
            ->approved()
            ->search($filters['search'] ?? null);

        if (!empty($filters['city'])) {
            $query->where(
                'city',
                $filters['city']
            );
        }

        if (!empty($filters['property_type'])) {
            $query->where(
                'property_type',
                $filters['property_type']
            );
        }

        if (!empty($filters['availability_status'])) {
            $query->where(
                'availability_status',
                $filters['availability_status']
            );
        }

        if (
            isset($filters['min_price'])
            && $filters['min_price'] !== ''
        ) {
            $query->where(
                'rental_price',
                '>=',
                $filters['min_price']
            );
        }

        if (
            isset($filters['max_price'])
            && $filters['max_price'] !== ''
        ) {
            $query->where(
                'rental_price',
                '<=',
                $filters['max_price']
            );
        }

        return $query
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(20)
            ->get();
    }
}
